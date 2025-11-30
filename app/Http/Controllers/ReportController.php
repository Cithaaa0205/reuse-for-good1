<?php

namespace App\Http\Controllers;

use App\Models\BarangDonasi;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Lapor barang dari halaman barang.
     */
    public function reportBarang(Request $request, BarangDonasi $barang)
    {
        $data = $this->validateReport($request);

        Report::create([
            'reporter_id'   => Auth::id(),
            'reported_type' => Report::TYPE_BARANG,
            'reported_id'   => $barang->id,
            'reason'        => $data['reason'],
            'status'        => Report::STATUS_BARU,
        ]);

        return back()->with('success', 'Laporan Anda terhadap barang ini sudah dikirim ke admin.');
    }

    /**
     * Lapor user dari halaman profil.
     */
    public function reportUser(Request $request, User $user)
    {
        $data = $this->validateReport($request);

        // Opsional: jangan izinkan lapor diri sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat melaporkan diri sendiri.');
        }

        Report::create([
            'reporter_id'   => Auth::id(),
            'reported_type' => Report::TYPE_USER,
            'reported_id'   => $user->id,
            'reason'        => $data['reason'],
            'status'        => Report::STATUS_BARU,
        ]);

        return back()->with('success', 'Laporan Anda terhadap user ini sudah dikirim ke admin.');
    }

    /**
     * Lapor pesan chat tertentu.
     */
    public function reportMessage(Request $request, Message $message)
    {
        $data = $this->validateReport($request);

        Report::create([
            'reporter_id'   => Auth::id(),
            'reported_type' => Report::TYPE_PESAN,
            'reported_id'   => $message->id,
            'reason'        => $data['reason'],
            'status'        => Report::STATUS_BARU,
        ]);

        return back()->with('success', 'Laporan chat sudah dikirim ke admin.');
    }

    /**
     * Validasi umum alasan laporan.
     */
    protected function validateReport(Request $request): array
    {
        return $request->validate([
            'reason' => 'required|string|max:500',
        ]);
    }
}
