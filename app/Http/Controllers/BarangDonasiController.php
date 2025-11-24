<?php

namespace App\Http\Controllers;

// ... (use statements)
use App\Models\BarangDonasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; // TAMBAHKAN INI

class BarangDonasiController extends Controller
{
    // Tampilkan semua barang (Etalase)
    public function index(Request $request)
    {
// ... (fungsi index tidak berubah)
// ...
        $query = BarangDonasi::where('status', 'Tersedia');

        // Filter Kategori
        if ($request->has('kategori')) {
            $kategoriSlug = $request->get('kategori');
            $query->whereHas('kategori', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        $barang = $query->latest()->paginate(20);
        $kategoris = Kategori::all(); // Untuk tombol filter
        
        // Ambil list ID favorit user
        $favoriteIds = [];
        if (Auth::check()) {
            $favoriteIds = Auth::user()->favorites()->pluck('barang_donasi_id')->toArray();
        }

        return view('barang.index', compact('barang', 'kategoris', 'favoriteIds')); // Tambahkan favoriteIds
    }

    // Tampilkan form donasi
    public function create()
// ... (fungsi create tidak berubah)
// ...
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    // Simpan donasi baru
    public function store(Request $request)
// ... (fungsi store tidak berubah)
// ...
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'kondisi' => 'required|string',
            'lokasi' => 'required|string',
            'foto_barang' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_pengambilan' => 'nullable|string',
        ]);

        $nama_file = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $nama_file = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/barang'), $nama_file);
        }

        BarangDonasi::create([
            'donatur_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
            'foto_barang_utama' => $nama_file,
            'catatan_pengambilan' => $request->catatan_pengambilan,
            'status' => 'Tersedia',
        ]);

        return redirect()->route('home')->with('success', 'Donasi berhasil diposting!');
    }

    // Tampilkan detail barang
    public function show($id)
// ... (fungsi show tidak berubah)
// ...
    {
        $barang = BarangDonasi::with('donatur', 'kategori')->findOrFail($id);
        $barangSerupa = BarangDonasi::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $id)
            ->where('status', 'Tersedia')
            ->take(5)
            ->get();
            
        $sudahDiajukan = false;
        if (Auth::check()) {
            $sudahDiajukan = \App\Models\RequestBarang::where('barang_donasi_id', $id)
                                ->where('penerima_id', Auth::id())
                                ->exists();
        }

        return view('barang.show', compact('barang', 'barangSerupa', 'sudahDiajukan'));
    }

    // === FUNGSI BARU UNTUK HAPUS DONASI ===
    /**
     * Hapus donasi barang.
     */
    public function destroy($id)
    {
        $barang = BarangDonasi::findOrFail($id);

        // Cek apakah user adalah pemilik donasi
        if (Auth::id() !== $barang->donatur_id) {
            return back()->with('error', 'Anda tidak berhak menghapus donasi ini.');
        }

        // Hapus file gambar dari server
        $path = public_path('uploads/barang/' . $barang->foto_barang_utama);
        if (File::exists($path)) {
            File::delete($path);
        }

        // Hapus data dari database
        $barang->delete();

        return redirect()->route('profile.show', Auth::user()->username)->with('success', 'Donasi berhasil dihapus.');
    }
}