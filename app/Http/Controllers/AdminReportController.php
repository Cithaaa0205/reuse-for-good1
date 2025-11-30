<?php

namespace App\Http\Controllers;

use App\Models\BarangDonasi;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    /**
     * List laporan + filter status.
     */
    public function index(Request $request)
    {
        $query = Report::with('reporter')
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Detail satu laporan.
     */
    public function show(Report $report)
    {
        $report->load('reporter', 'handler');

        $target = $report->target_model; // bisa User / BarangDonasi / Message

        return view('admin.reports.show', compact('report', 'target'));
    }

    /**
     * Update status laporan + catatan admin.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $data = $request->validate([
            'status'      => 'required|in:baru,diproses,selesai',
            'admin_notes' => 'nullable|string',
        ]);

        $report->status = $data['status'];

        if (!empty($data['admin_notes'])) {
            $report->admin_notes = $data['admin_notes'];
        }

        if ($report->status !== Report::STATUS_BARU) {
            $report->handled_by = Auth::id();
            $report->handled_at = now();
        }

        $report->save();

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Suspend user berdasarkan laporan.
     */
    public function suspendUser(Request $request, Report $report)
    {
        $data = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user = $this->resolveUserFromReport($report);

        if (!$user) {
            return back()->with('error', 'User yang dilaporkan tidak ditemukan.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mensuspend diri sendiri.');
        }

        $user->status            = User::STATUS_SUSPENDED;
        $user->status_reason     = $data['reason'];
        $user->status_changed_at = now();
        $user->save();

        $report->status      = Report::STATUS_DIPROSES;
        $report->handled_by  = Auth::id();
        $report->handled_at  = now();
        $report->admin_notes = trim(($report->admin_notes ?? '') . "\n\n[Suspend] " . $data['reason']);
        $report->save();

        return back()->with('success', 'User berhasil disuspend berdasarkan laporan ini.');
    }

    /**
     * Hide barang berdasarkan laporan.
     */
    public function hideBarang(Request $request, Report $report)
    {
        $barang = null;

        if ($report->reported_type === Report::TYPE_BARANG) {
            $barang = BarangDonasi::find($report->reported_id);
        }

        if (!$barang) {
            return back()->with('error', 'Barang yang dilaporkan tidak ditemukan.');
        }

        $barang->is_hidden = true;
        $barang->save();

        $note = $request->input('admin_notes', 'Barang disembunyikan oleh admin.');

        $report->status      = Report::STATUS_DIPROSES;
        $report->handled_by  = Auth::id();
        $report->handled_at  = now();
        $report->admin_notes = trim(($report->admin_notes ?? '') . "\n\n[Hide barang] " . $note);
        $report->save();

        return back()->with('success', 'Barang berhasil disembunyikan dari etalase.');
    }

    /**
     * Bantu cari User yang relevan dari sebuah report.
     */
    protected function resolveUserFromReport(Report $report): ?User
    {
        if ($report->reported_type === Report::TYPE_USER) {
            return User::find($report->reported_id);
        }

        if ($report->reported_type === Report::TYPE_BARANG) {
            $barang = BarangDonasi::find($report->reported_id);
            return $barang?->donatur;
        }

        if ($report->reported_type === Report::TYPE_PESAN) {
            $message = Message::find($report->reported_id);
            return $message?->sender;
        }

        return null;
    }
}
