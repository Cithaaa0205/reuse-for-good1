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
     * Etalase barang – dipakai di:
     * - route('barang.index')
     * - tombol "Mulai Jelajahi" & "Terima Barang" di home.blade.php
     */
    public function index(Request $request)
    {
        // Hanya barang yang masih tersedia
        $query = BarangDonasi::where('status', 'Tersedia');

        // Filter kategori via ?kategori=slug
        if ($request->has('kategori')) {
            $kategoriSlug = $request->get('kategori');
            $query->whereHas('kategori', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug);
            });
        }

        $barang     = $query->latest()->paginate(20);
        $kategoris  = Kategori::all();

        // Ambil list ID favorit user (untuk icon favorit di card)
        $favoriteIds = [];
        if (Auth::check()) {
            $user = Auth::user();

            // Coba ambil kolom barang_donasi_id (jika relasi favorites menyimpan pivot/kolom tersebut)
            $favIds = $user->favorites()->pluck('barang_donasi_id')->toArray();

            // Fallback: kalau relasi favorites mereturn model BarangDonasi langsung, pluck id
            if (empty($favIds)) {
                $favIds = $user->favorites()->pluck('id')->toArray();
            }

            $favoriteIds = $favIds;
        }

        return view('barang.index', compact('barang', 'kategoris', 'favoriteIds'));
    }

    /**
     * Form donasi barang – route('barang.create')
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    /**
     * Simpan donasi baru – route('barang.store')
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'          => 'required|string|max:255',
            'deskripsi'            => 'required|string',
            'kategori_id'          => 'required|exists:kategoris,id',
            'kondisi'              => 'required|string',
            'lokasi'               => 'required|string',
            'foto_barang'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan_pengambilan'  => 'nullable|string',
        ]);

        // Upload foto utama
        $nama_file = null;
        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');

            $nama_file = time() . '_' .
                Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
                '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/barang'), $nama_file);
        }

        BarangDonasi::create([
            'donatur_id'          => Auth::id(),
            'kategori_id'         => $request->kategori_id,
            'nama_barang'         => $request->nama_barang,
            'deskripsi'           => $request->deskripsi,
            'kondisi'             => $request->kondisi,
            'lokasi'              => $request->lokasi,
            'alamat_lengkap'      => null,
            'foto_barang_utama'   => $nama_file,
            'foto_barang_lainnya' => null,
            'catatan_pengambilan' => $request->catatan_pengambilan,
            'status'              => 'Tersedia',
        ]);

        return redirect()->route('home')->with('success', 'Donasi berhasil diposting!');
    }

    /**
     * Detail barang – route('barang.show', $id)
     */
    public function show($id)
    {
        $barang = BarangDonasi::with(['donatur', 'kategori'])->findOrFail($id);

        // Barang serupa (kategori sama, status tersedia, bukan diri sendiri)
        $barangSerupa = BarangDonasi::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $id)
            ->where('status', 'Tersedia')
            ->take(5)
            ->get();

        // Cek apakah user ini sudah pernah mengajukan permintaan barang ini
        $sudahDiajukan = false;
        if (Auth::check()) {
            $sudahDiajukan = \App\Models\RequestBarang::where('barang_donasi_id', $id)
                ->where('penerima_id', Auth::id())
                ->exists();
        }

        return view('barang.show', compact('barang', 'barangSerupa', 'sudahDiajukan'));
    }

    /**
     * Hapus donasi – route('barang.destroy', $id)
     */
    public function destroy($id)
    {
        $barang = BarangDonasi::findOrFail($id);

        // Pastikan yang hapus adalah pemilik donasi
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

        return redirect()
            ->route('profile.show', Auth::user()->username)
            ->with('success', 'Donasi berhasil dihapus.');
    }
}
