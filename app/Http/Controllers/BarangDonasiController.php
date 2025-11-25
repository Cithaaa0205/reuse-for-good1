<?php

namespace App\Http\Controllers;

use App\Models\BarangDonasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BarangDonasiController extends Controller
{
    /**
     * Menampilkan daftar barang donasi yang tersedia (Etalase).
     * Hasil hanya ditampilkan jika ada parameter 'search' atau 'kategori'.
     */
    public function index(Request $request)
    {
        $isSearchActive = $request->filled('search') || $request->filled('kategori');

        // Selalu mulai dengan barang yang statusnya 'Tersedia'
        $query = BarangDonasi::where('status', 'Tersedia');

        // ==========================
        // 🔍 FITUR SEARCH
        // ==========================
        if ($request->filled('search')) {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('provinsi', 'like', "%{$keyword}%")
                    ->orWhere('kabupaten', 'like', "%{$keyword}%")
                    ->orWhereHas('kategori', function ($qc) use ($keyword) {
                        $qc->where('nama_kategori', 'like', "%{$keyword}%");
                    });
            });
        }

        // ==========================
        // 🔎 FILTER KATEGORI
        // ==========================
        if ($request->filled('kategori')) {
            $kategoriSlug = $request->kategori;
            $query->whereHas('kategori', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        // Ambil data hanya jika ada pencarian atau filter yang aktif
        // Jika tidak aktif, $barang akan kosong (default query Laravel)
        $barang = $query->latest()->paginate(20);
        $kategoris = Kategori::all();

        // Favorite user (jika login)
        $favoriteIds = [];
        if (Auth::check()) {
            $favoriteIds = Auth::user()
                ->favorites()
                ->pluck('barang_donasis.id')
                ->toArray();
        }

        return view('barang.index', compact('barang', 'kategoris', 'favoriteIds', 'isSearchActive'));
    }

    /**
     * Menampilkan form untuk membuat donasi baru.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Simpan donasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'foto_barang' => 'required|array',
            'foto_barang.*' => 'image|mimes:jpeg,png,jpg|max:10240',
            'catatan_pengambilan' => 'nullable|string',
        ]);

        // === Upload foto multiple ===
        $fotoUtama = null;
        $fotoLain = [];

        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $index => $file) {
                $namaFile = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/barang'), $namaFile);

                if ($index === 0) {
                    $fotoUtama = $namaFile;
                } else {
                    $fotoLain[] = $namaFile;
                }
            }
        }

        BarangDonasi::create([
            'donatur_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'kondisi' => $request->kondisi,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'foto_barang_utama' => $fotoUtama,
            // Asumsi model BarangDonasi menggunakan $casts = ['foto_barang_lainnya' => 'array'];
            // Jika tidak, biarkan 'foto_barang_lainnya' => json_encode($fotoLain)
            'foto_barang_lainnya' => json_encode($fotoLain), 
            'catatan_pengambilan' => $request->catatan_pengambilan,
            'status' => 'Tersedia',
        ]);

        return redirect()->route('home')->with('success', 'Donasi berhasil diposting!');
    }

    /**
     * Menampilkan detail barang tertentu.
     * Menggunakan Route Model Binding: $barang otomatis di-fetch dari ID di route.
     */
    public function show(BarangDonasi $barang)
    {
        $barang->load('donatur', 'kategori'); // Memastikan relasi dimuat

        $barangSerupa = BarangDonasi::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $barang->id)
            ->where('status', 'Tersedia')
            ->take(5)
            ->get();

        $sudahDiajukan = false;
        if (Auth::check()) {
            $sudahDiajukan = \App\Models\RequestBarang::where('barang_donasi_id', $barang->id)
                ->where('penerima_id', Auth::id())
                ->exists();
        }

        return view('barang.show', compact('barang', 'barangSerupa', 'sudahDiajukan'));
    }

    /**
     * Menghapus barang donasi.
     * Menggunakan Route Model Binding: $barang otomatis di-fetch dari ID di route.
     */
    public function destroy(BarangDonasi $barang)
    {
        if (Auth::id() !== $barang->donatur_id) {
            return back()->with('error', 'Anda tidak berhak menghapus donasi ini.');
        }

        // Hapus file UTAMA
        $pathUtama = public_path('uploads/barang/' . $barang->foto_barang_utama);
        if (File::exists($pathUtama)) File::delete($pathUtama);

        // Hapus file LAINNYA
        if ($barang->foto_barang_lainnya) {
            // Jika Anda menggunakan Model Casting (array), ganti json_decode menjadi $barang->foto_barang_lainnya
            foreach (json_decode($barang->foto_barang_lainnya) as $foto) {
                $path = public_path('uploads/barang/' . $foto);
                if (File::exists($path)) File::delete($path);
            }
        }

        $barang->delete();

        return redirect()->route('profile.show', Auth::user()->username)->with('success', 'Donasi berhasil dihapus.');
    }
}