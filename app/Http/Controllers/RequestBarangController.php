<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestBarang;
use App\Models\BarangDonasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller; // <-- PERUBAHAN DI SINI

class RequestBarangController extends Controller
{
    /**
     * Menyimpan pengajuan permintaan barang baru.
     * (Dipanggil dari Gambar 9)
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_donasi_id' => 'required|exists:barang_donasis,id',
            // Anda bisa tambahkan validasi untuk alasan jika diperlukan
            // 'alasan_permintaan' => 'required|string|min:10',
        ]);

        $barangId = $request->input('barang_donasi_id');
        $userId = Auth::id();

        // Cek apakah barang masih tersedia
        $barang = BarangDonasi::find($barangId);
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
            'penerima_id' => $userId,
            'barang_donasi_id' => $barangId,
            'status' => 'Diajukan',
            // 'alasan_permintaan' => $request->input('alasan_permintaan'), // Uncomment jika ada field alasan
        ]);

        // Opsional: Ubah status barang menjadi 'Dipesan' agar tidak bisa diminta orang lain
        // $barang->status = 'Dipesan';
        // $barang->save();

        return redirect()->route('barang.show', $barangId)->with('success', 'Permintaan berhasil diajukan!');
    }
}