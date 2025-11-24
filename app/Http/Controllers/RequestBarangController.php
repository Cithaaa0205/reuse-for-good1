<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestBarang;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class RequestBarangController extends Controller
{
    /**
     * Menyimpan pengajuan permintaan barang baru (Penerima).
     */
    public function store(Request $request, $barangId)
    {
        $userId = Auth::id();

        // Cek apakah barang ada
        $barang = BarangDonasi::findOrFail($barangId);

        if ($barang->donatur_id == $userId) {
             return back()->with('error', 'Anda tidak dapat meminta barang Anda sendiri.');
        }

        if ($barang->status !== 'Tersedia') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }

        // Cek apakah sudah pernah request
        $existingRequest = RequestBarang::where('barang_donasi_id', $barangId)
                                        ->where('penerima_id', $userId)
                                        ->exists();

        if ($existingRequest) {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk barang ini.');
        }

        RequestBarang::create([
            'penerima_id' => $userId,
            'barang_donasi_id' => $barangId,
            'status' => 'Diajukan', // Status awal
            'alasan_permintaan' => 'Saya sangat membutuhkan barang ini.', // Default atau dari input
        ]);

        return back()->with('success', 'Permintaan berhasil diajukan! Tunggu konfirmasi pendonasi.');
    }

    /**
     * Menampilkan halaman Kelola Pengajuan (Pendonasi).
     * Menampilkan daftar orang yang meminta barang kita.
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua request yang masuk UNTUK barang-barang milik user yang login
        $requests = RequestBarang::whereHas('barangDonasi', function($q) use ($userId) {
            $q->where('donatur_id', $userId);
        })->with(['penerima', 'barangDonasi'])->latest()->get();

        // Kelompokkan berdasarkan status untuk Tab
        $menunggu = $requests->where('status', 'Diajukan');
        $diterima = $requests->where('status', 'Disetujui'); // Kita pakai istilah 'Disetujui' di DB, 'Diterima' di UI
        $ditolak = $requests->where('status', 'Ditolak');

        return view('profile.manage_requests', compact('menunggu', 'diterima', 'ditolak'));
    }

    /**
     * Mengubah status pengajuan (Terima/Tolak).
     */
    public function updateStatus($id, $status)
    {
        $requestBarang = RequestBarang::findOrFail($id);
        
        // Validasi status yang diperbolehkan
        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        // Pastikan yang mengubah adalah pemilik barang (Pendonasi)
        if ($requestBarang->barangDonasi->donatur_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak berhak mengubah status ini.');
        }

        // Update status request ini
        $requestBarang->status = $status;
        $requestBarang->save();

        // LOGIKA TAMBAHAN:
        // Jika status 'Disetujui' (Diterima), maka:
        if ($status === 'Disetujui') {
            // 1. Ubah status barang menjadi 'Dipesan' atau 'Tidak Tersedia'
            $barang = $requestBarang->barangDonasi;
            $barang->status = 'Dipesan'; // Atau 'Selesai'
            $barang->save();

            // 2. Tolak otomatis semua request lain untuk barang yang sama
            RequestBarang::where('barang_donasi_id', $requestBarang->barang_donasi_id)
                ->where('id', '!=', $id)
                ->update(['status' => 'Ditolak']);
        }
        // Jika 'Ditolak' dan sebelumnya barang 'Dipesan' karena ini, kembalikan jadi 'Tersedia'? 
        // (Opsional, tergantung logika bisnis Anda. Sederhananya biarkan saja dulu)

        $message = $status === 'Disetujui' ? 'Pengajuan diterima!' : 'Pengajuan ditolak.';
        return back()->with('success', $message);
    }
}