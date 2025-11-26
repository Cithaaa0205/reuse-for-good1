<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestBarang;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;

class RequestBarangController extends Controller
{
    /**
     * Simpan pengajuan permintaan barang
     */
    public function store(Request $request, BarangDonasi $barang)
    {
        $userId = Auth::id();

        // Cek pemilik barang
        if ($barang->donatur_id == $userId) {
            return back()->with('error', 'Anda tidak dapat meminta barang Anda sendiri.');
        }

        // Cek status barang
        if ($barang->status !== 'Tersedia') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }

        // Cek request sebelumnya
        $existingRequest = RequestBarang::where('barang_donasi_id', $barang->id)
                            ->where('penerima_id', $userId)
                            ->first();

        // Jika sudah request & status Ditolak -> hapus dan izinkan request ulang
        if ($existingRequest && $existingRequest->status === 'Ditolak') {
            $existingRequest->delete();
        }

        // Jika sudah request & status masih aktif (Diajukan / Disetujui)
        if ($existingRequest && $existingRequest->status !== 'Ditolak') {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk barang ini.');
        }

        // Buat request baru
        RequestBarang::create([
            'penerima_id' => $userId,
            'barang_donasi_id' => $barang->id,
            'status' => 'Diajukan',
            'alasan_permintaan' => 'Saya sangat membutuhkan barang ini.',
        ]);

        return back()->with('success', 'Permintaan berhasil diajukan! Tunggu konfirmasi pendonasi.');
    }

    /**
     * Kelola Pengajuan Pendonasi
     */
    public function index()
    {
        $userId = Auth::id();

        $requests = RequestBarang::whereHas('barangDonasi', function($q) use ($userId) {
            $q->where('donatur_id', $userId);
        })
        ->with(['penerima', 'barangDonasi'])
        ->latest()
        ->get();

        $menunggu = $requests->where('status', 'Diajukan');
        $diterima = $requests->where('status', 'Disetujui');
        $ditolak = $requests->where('status', 'Ditolak');

        return view('profile.manage_requests', compact('menunggu', 'diterima', 'ditolak'));
    }

    /**
     * Update status pengajuan
     */
    public function updateStatus($id, $status)
    {
        $requestBarang = RequestBarang::findOrFail($id);

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Pastikan pemilik barang yang mengubah status
        if ($requestBarang->barangDonasi->donatur_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        // Update status request
        $requestBarang->status = $status;
        $requestBarang->save();

        // Jika disetujui
        if ($status === 'Disetujui') {
            $barang = $requestBarang->barangDonasi;
            $barang->status = 'Dipesan';
            $barang->save();

            // Otomatis tolak request lainnya
            RequestBarang::where('barang_donasi_id', $requestBarang->barang_donasi_id)
                ->where('id', '!=', $id)
                ->update(['status' => 'Ditolak']);
        }

        return back()->with('success', $status === 'Disetujui' ? 'Pengajuan diterima!' : 'Pengajuan ditolak.');
    }
}
