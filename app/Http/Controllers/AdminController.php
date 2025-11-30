<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BarangDonasi;
use App\Models\RequestBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // =========================
    // DASHBOARD ADMIN
    // =========================
    public function dashboard()
    {
        // 1. Statistik ringkas
        $totalUsers    = User::count();
        $totalBarang   = BarangDonasi::count();
        $totalRequests = RequestBarang::count();

        $requestsByStatus = RequestBarang::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status'); // ['Diajukan' => x, ...]

        $requestsDiajukan  = $requestsByStatus['Diajukan'] ?? 0;
        $requestsDisetujui = $requestsByStatus['Disetujui'] ?? 0;
        $requestsDitolak   = $requestsByStatus['Ditolak'] ?? 0;

        $barangTersedia = BarangDonasi::where('status', 'Tersedia')->count();
        $barangDipesan  = BarangDonasi::where('status', 'Dipesan')->count();

        // 2. Top kota / kabupaten
        $topKotaByBarang = BarangDonasi::select('kabupaten', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kabupaten')
            ->groupBy('kabupaten')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topKotaByUser = User::select('kabupaten', DB::raw('COUNT(*) as total'))
            ->whereNotNull('kabupaten')
            ->groupBy('kabupaten')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 3. Aktivitas terbaru
        $latestBarang = BarangDonasi::with(['kategori', 'donatur'])
            ->latest()
            ->limit(5)
            ->get();

        $latestRequests = RequestBarang::with(['barangDonasi', 'penerima'])
            ->latest()
            ->limit(5)
            ->get();

        $latestUsers = User::latest()
            ->limit(5)
            ->get();

        // 4. Peringatan
        $thresholdDate = Carbon::now()->subDays(20);

        $oldAvailableItems = BarangDonasi::where('status', 'Tersedia')
            ->where('created_at', '<=', $thresholdDate)
            ->get();

        $oldAvailableItemsCount = $oldAvailableItems->count();

        $usersWithoutLocation = User::where(function ($q) {
                $q->whereNull('provinsi')
                  ->orWhereNull('kabupaten')
                  ->orWhere('provinsi', '')
                  ->orWhere('kabupaten', '');
            })
            ->get();

        $usersWithoutLocationCount = $usersWithoutLocation->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBarang',
            'totalRequests',
            'requestsDiajukan',
            'requestsDisetujui',
            'requestsDitolak',
            'barangTersedia',
            'barangDipesan',
            'topKotaByBarang',
            'topKotaByUser',
            'latestBarang',
            'latestRequests',
            'latestUsers',
            'oldAvailableItems',
            'oldAvailableItemsCount',
            'usersWithoutLocation',
            'usersWithoutLocationCount'
        ));
    }

    // =========================
    // Manajemen Pengguna
    // =========================
    public function indexUsers()
    {
        $users = User::orderBy('id')->get();
        return view('admin.user.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'username'       => 'required|string|max:255|alpha_dash|unique:users,username,' . $user->id,
            'nomor_telepon'  => 'nullable|string|max:15',
            'role'           => 'required|in:donatur,admin',
        ]);

        $user->update([
            'nama_lengkap'  => $request->nama_lengkap,
            'username'      => $request->username,
            'nomor_telepon' => $request->nomor_telepon,
            'role'          => $request->role,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }

    // --------- Aksi Status User ---------

    public function suspendUser(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa mensuspend akun Anda sendiri.');
        }

        $data = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user->status            = User::STATUS_SUSPENDED;
        $user->status_reason     = $data['reason'];
        $user->status_changed_at = now();
        $user->save();

        return back()->with('success', 'User berhasil disuspend.');
    }

    public function banUser(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa mem-ban akun Anda sendiri.');
        }

        $data = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user->status            = User::STATUS_BANNED;
        $user->status_reason     = $data['reason'];
        $user->status_changed_at = now();
        $user->save();

        return back()->with('success', 'User berhasil diblokir (banned).');
    }

    public function restoreUser(User $user)
    {
        $user->status            = User::STATUS_AKTIF;
        $user->status_reason     = null;
        $user->status_changed_at = now();
        $user->save();

        return back()->with('success', 'Status user berhasil dipulihkan ke aktif.');
    }

    // =========================
    // Manajemen Barang Donasi
    // =========================
    public function indexBarang()
    {
        $barang = BarangDonasi::with('kategori', 'donatur')
            ->orderByDesc('created_at')
            ->get();

        $totalBarang   = $barang->count();
        $totalTersedia = $barang->where('status', 'Tersedia')->count();
        $totalDipesan  = $barang->where('status', 'Dipesan')->count();
        $totalHidden   = $barang->where('is_hidden', true)->count();

        return view('admin.barang.index', compact(
            'barang',
            'totalBarang',
            'totalTersedia',
            'totalDipesan',
            'totalHidden'
        ));
    }

    public function destroyBarang(BarangDonasi $barang)
    {
        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus!');
    }

    public function hideBarang(BarangDonasi $barang)
    {
        $barang->is_hidden = true;
        $barang->save();

        return back()->with('success', 'Barang berhasil disembunyikan dari etalase.');
    }

    public function unhideBarang(BarangDonasi $barang)
    {
        $barang->is_hidden = false;
        $barang->save();

        return back()->with('success', 'Barang berhasil ditampilkan kembali di etalase.');
    }
}
