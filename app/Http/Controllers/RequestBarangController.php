<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestBarang;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;

class RequestBarangController extends Controller
{
    /**
     * Dashboard Pengajuan untuk Donatur
     * route('request.dashboard') → /dashboard/pengajuan
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua barang milik donatur
        $barangIds = BarangDonasi::where('donatur_id', $user->id)->pluck('id');

        // Ambil semua pengajuan untuk barang-barang tersebut
        $pengajuan = RequestBarang::with(['barangDonasi', 'penerima'])
            ->whereIn('barang_donasi_id', $barangIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donatur.dashboard_pengajuan', compact('pengajuan'));
    }

    /**
     * Menyimpan pengajuan permintaan barang baru.
     * route('request.store')
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_donasi_id' => 'required|exists:barang_donasis,id',
        ]);

        $barangId = $request->input('barang_donasi_id');
        $userId   = Auth::id();

        // Cek apakah barang masih tersedia
        $barang = BarangDonasi::findOrFail($barangId);
        if ($barang->status !== 'Tersedia') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }

        // Cek apakah user adalah pemilik barang
        if ($barang->donatur_id == $userId) {
            return back()->with('error', 'Anda tidak dapat meminta barang Anda sendiri.');
        }

        // Cek apakah user sudah pernah request barang ini
        $existingRequest = RequestBarang::where('barang_donasi_id', $barangId)
            ->where('penerima_id', $userId)
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk barang ini.');
        }

        // Buat request baru
        RequestBarang::create([
            'penerima_id'      => $userId,
            'barang_donasi_id' => $barangId,
            'status'           => 'Diajukan',
        ]);

        return redirect()
            ->route('barang.show', $barangId)
            ->with('success', 'Permintaan berhasil diajukan!');
    }

    /**
     * Setujui permintaan
     * route('request.approve', $id)
     */
    public function approve($id)
    {
        $req    = RequestBarang::with('barangDonasi')->findOrFail($id);
        $barang = $req->barangDonasi;

        // Ubah status permintaan
        $req->update(['status' => 'Disetujui']);

        if ($barang) {
            // Ubah status barang jadi tidak tersedia
            $barang->update(['status' => 'Tidak Tersedia']);

            // Tolak semua pengajuan lain untuk barang ini
            RequestBarang::where('barang_donasi_id', $barang->id)
                ->where('id', '!=', $req->id)
                ->update(['status' => 'Ditolak']);
        }

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    /**
     * Tolak permintaan
     * route('request.reject', $id)
     */
    public function reject($id)
    {
        $req = RequestBarang::findOrFail($id);
        $req->update(['status' => 'Ditolak']);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
