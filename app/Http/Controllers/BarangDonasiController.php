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
    public function index(Request $request)
    {
        $query = BarangDonasi::where('status', 'Tersedia');

        if ($request->has('kategori')) {
            $kategoriSlug = $request->get('kategori');
            $query->whereHas('kategori', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        $barang = $query->latest()->paginate(20);
        $kategoris = Kategori::all();

        $favoriteIds = [];
        if (Auth::check()) {
            $favoriteIds = Auth::user()->favorites()->pluck('barang_donasis.id')->toArray();
        }

        return view('barang.index', compact('barang', 'kategoris', 'favoriteIds'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    // ===========================
    // Simpan donasi baru
    // ===========================
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
    'foto_barang_lainnya' => json_encode($fotoLain),
    'catatan_pengambilan' => $request->catatan_pengambilan,
    'status' => 'Tersedia',
]);


        return redirect()->route('home')->with('success', 'Donasi berhasil diposting!');
    }

    public function show($id)
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

    public function destroy($id)
    {
        $barang = BarangDonasi::findOrFail($id);

        if (Auth::id() !== $barang->donatur_id) {
            return back()->with('error', 'Anda tidak berhak menghapus donasi ini.');
        }

        // Hapus file UTAMA
        $pathUtama = public_path('uploads/barang/' . $barang->foto_barang_utama);
        if (File::exists($pathUtama)) File::delete($pathUtama);

        // Hapus file LAINNYA
        if ($barang->foto_barang_lainnya) {
            foreach (json_decode($barang->foto_barang_lainnya) as $foto) {
                $path = public_path('uploads/barang/' . $foto);
                if (File::exists($path)) File::delete($path);
            }
        }

        $barang->delete();

        return redirect()->route('profile.show', Auth::user()->username)->with('success', 'Donasi berhasil dihapus.');
    }
}