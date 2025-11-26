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

        if ($barang->donatur_id == $userId) {
            return back()->with('error', 'Anda tidak dapat meminta barang Anda sendiri.');
        }

        if ($barang->status !== 'Tersedia') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }

        if (RequestBarang::where('barang_donasi_id', $barang->id)
            ->where('penerima_id', $userId)
            ->exists()) 
        {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk barang ini.');
        }

        RequestBarang::create([
            'penerima_id' => $userId,
            'barang_donasi_id' => $barang->id,
            'status' => 'Diajukan',
            'alasan_permintaan' => 'Saya sangat membutuhkan barang ini.',
        ]);

        return back()->with('success', 'Permintaan berhasil diajukan! Tunggu konfirmasi pendonasi.');
    }

    /**
     * Kelola Pengajuan
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
     * Update status Pengajuan
     */
    public function updateStatus($id, $status)
    {
        $requestBarang = RequestBarang::findOrFail($id);

        if (!in_array($status, ['Disetujui', 'Ditolak'])) {
            return back()->with('error', 'Status tidak valid.');
        }

        if ($requestBarang->barangDonasi->donatur_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $requestBarang->status = $status;
        $requestBarang->save();

        if ($status === 'Disetujui') {
            $barang = $requestBarang->barangDonasi;
            $barang->status = 'Dipesan';
            $barang->save();

            RequestBarang::where('barang_donasi_id', $requestBarang->barang_donasi_id)
                ->where('id', '!=', $id)
                ->update(['status' => 'Ditolak']);
        }

        return back()->with('success', $status === 'Disetujui' ? 'Pengajuan diterima!' : 'Pengajuan ditolak.');
    }
}
