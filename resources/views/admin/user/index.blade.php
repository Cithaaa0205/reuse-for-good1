@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
@php
    use App\Models\User as UserModel;

    $collection   = $users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->getCollection() : collect($users);
    $totalUsers   = $collection->count();
    $totalAdmins  = $collection->where('role', 'admin')->count();
    $totalUserBiasa = $totalUsers - $totalAdmins;

    $totalAktif     = $collection->where('status', UserModel::STATUS_AKTIF)->count();
    $totalSuspended = $collection->where('status', UserModel::STATUS_SUSPENDED)->count();
    $totalBanned    = $collection->where('status', UserModel::STATUS_BANNED)->count();
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER ADMIN PAGE --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                Panel Admin • Manajemen Pengguna
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
                Manajemen Pengguna
            </h1>
            <p class="text-sm text-slate-500 mt-1 max-w-xl">
                Kelola akun dan keamanan pengguna di Reuse For Good. Admin bisa mengatur role serta status
                <span class="font-semibold">aktif / suspended / banned</span>.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 justify-end">
            <div class="px-3 py-1.5 rounded-full text-[11px] font-medium bg-slate-900 text-sky-100 shadow-sm">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                    Akses Khusus Admin
                </span>
            </div>
        </div>
    </div>

    {{-- CHIP STAT & SEARCH --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2 text-[11px] sm:text-xs">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-900 text-sky-100 shadow-sm">
                <i data-lucide="users" class="w-3 h-3"></i>
                <span><strong>{{ $totalUsers }}</strong> total pengguna</span>
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                <i data-lucide="shield" class="w-3 h-3"></i>
                <span>{{ $totalAdmins }} admin</span>
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                <i data-lucide="user" class="w-3 h-3"></i>
                <span>{{ $totalUserBiasa }} user biasa</span>
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                <span>{{ $totalAktif }} aktif</span>
            </span>
            @if($totalSuspended > 0)
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                    <i data-lucide="pause-circle" class="w-3 h-3"></i>
                    <span>{{ $totalSuspended }} suspended</span>
                </span>
            @endif
            @if($totalBanned > 0)
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
                    <i data-lucide="slash" class="w-3 h-3"></i>
                    <span>{{ $totalBanned }} banned</span>
                </span>
            @endif
        </div>

        {{-- Search --}}
        <div class="w-full md:w-72">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input
                    id="user-search"
                    type="text"
                    placeholder="Cari nama, email, atau username..."
                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-700
                           bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition shadow-sm"
                >
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="flex items-start gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- TABLE WRAPPER --}}
    <div class="rounded-3xl bg-gradient-to-br from-slate-900/5 via-blue-100/10 to-sky-100/20 p-[1.5px] shadow-xl shadow-slate-200/60">
        <div class="overflow-hidden bg-white/95 backdrop-blur rounded-[1.4rem] border border-slate-100">
            <div class="px-4 sm:px-6 py-3 border-b border-slate-100 flex items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-900">
                    Daftar Pengguna
                </h2>
                <span class="text-[11px] text-slate-400 hidden sm:inline">
                    Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="user-table" class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600">
                            <th class="px-4 py-3 text-left font-semibold">ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Username</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">No. Telepon</th>
                            <th class="px-4 py-3 text-left font-semibold">Role</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold w-64">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            @php
                                $rowText = strtolower(
                                    ($user->nama_lengkap ?? '') . ' ' .
                                    ($user->username ?? '') . ' ' .
                                    ($user->email ?? '') . ' ' .
                                    ($user->nomor_telepon ?? '')
                                );

                                $status = $user->status ?? UserModel::STATUS_AKTIF;
                                $statusLabel = match ($status) {
                                    UserModel::STATUS_AKTIF     => 'Aktif',
                                    UserModel::STATUS_SUSPENDED => 'Suspended',
                                    UserModel::STATUS_BANNED    => 'Banned',
                                    default                     => ucfirst($status),
                                };

                                $statusClasses = match ($status) {
                                    UserModel::STATUS_AKTIF     => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                                    UserModel::STATUS_SUSPENDED => 'bg-amber-50 text-amber-700 border border-amber-100',
                                    UserModel::STATUS_BANNED    => 'bg-rose-50 text-rose-700 border border-rose-100',
                                    default                     => 'bg-slate-50 text-slate-600 border border-slate-200',
                                };

                                $statusDot = match ($status) {
                                    UserModel::STATUS_AKTIF     => 'bg-emerald-500',
                                    UserModel::STATUS_SUSPENDED => 'bg-amber-500',
                                    UserModel::STATUS_BANNED    => 'bg-rose-500',
                                    default                     => 'bg-slate-400',
                                };
                            @endphp

                            <tr class="hover:bg-slate-50/80 transition" data-row="{{ $rowText }}">
                                {{-- ID --}}
                                <td class="px-4 py-3 text-slate-500 align-top">{{ $user->id }}</td>

                                {{-- Nama + avatar kecil --}}
                                <td class="px-4 py-3 align-top">
                                    <div class="flex items-center gap-2">
                                        <div class="hidden sm:flex h-8 w-8 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold items-center justify-center">
                                            {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->nama_lengkap), 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs sm:text-sm">
                                                {{ $user->nama_lengkap }}
                                            </p>
                                            <p class="text-[11px] text-slate-400 sm:hidden">
                                                @<span>{{ $user->username }}</span>
                                            </p>
                                            @if($user->status_reason)
                                                <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1" title="{{ $user->status_reason }}">
                                                    {{ $user->status_reason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Username (desktop) --}}
                                <td class="px-4 py-3 text-slate-600 align-top hidden sm:table-cell">
                                    @<span>{{ $user->username }}</span>
                                </td>

                                {{-- Email --}}
                                <td class="px-4 py-3 text-slate-600 align-top">
                                    {{ $user->email }}
                                </td>

                                {{-- Telepon --}}
                                <td class="px-4 py-3 text-slate-600 align-top hidden md:table-cell">
                                    {{ $user->nomor_telepon ?? '-' }}
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-3 align-top">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold 
                                        {{ $user->role === 'admin' 
                                            ? 'bg-purple-50 text-purple-700 border border-purple-100' 
                                            : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                            {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                        {{ $user->role === 'admin' ? 'admin' : 'user' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3 align-top">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClasses }}"
                                        title="{{ $user->status_reason }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $statusDot }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                    @if($user->status_changed_at)
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ $user->status_changed_at->format('d M Y H:i') }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Aksi --}}
<td class="px-4 py-3 align-top">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-1.5 min-w-[260px]">

        {{-- Edit --}}
        <a href="{{ route('admin.users.edit', $user->id) }}"
           class="inline-flex w-full items-center justify-center gap-1.5
                  px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap
                  bg-amber-400 hover:bg-amber-500 text-white shadow-sm transition">
            <i data-lucide="edit-2" class="w-3 h-3"></i>
            Edit
        </a>

        {{-- Jangan tampilkan tombol status / hapus untuk diri sendiri --}}
        @if(auth()->id() !== $user->id)

            {{-- Suspend (hanya jika masih aktif) --}}
            @if($status === UserModel::STATUS_AKTIF)
                <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST"
                      onsubmit="return handleUserStatusAction(this, 'suspend');"
                      class="inline-flex w-full">
                    @csrf
                    <input type="hidden" name="reason">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5
                                   px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap
                                   bg-slate-200 text-slate-700 border border-slate-200 hover:bg-slate-300 transition">
                        <i data-lucide="pause-circle" class="w-3 h-3"></i>
                        Suspend
                    </button>
                </form>
            @endif

            {{-- Ban (kecuali sudah banned) --}}
            @if($status !== UserModel::STATUS_BANNED)
                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST"
                      onsubmit="return handleUserStatusAction(this, 'ban');"
                      class="inline-flex w-full">
                    @csrf
                    <input type="hidden" name="reason">
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5
                                   px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap
                                   bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                        <i data-lucide="slash" class="w-3 h-3"></i>
                        Ban
                    </button>
                </form>
            @endif

        {{-- Baris 2: Pulihkan + Hapus --}}
        @if($status !== UserModel::STATUS_AKTIF)
                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST"
                      class="inline-flex w-full">
                    @csrf
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-1.5
                                   px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap
                                   bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition">
                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                        Pulihkan
                    </button>
                </form>
            @endif

            {{-- Hapus --}}
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                  class="inline-flex w-full"
                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-1.5
                               px-3 py-1.5 rounded-full text-[11px] font-semibold whitespace-nowrap
                               bg-slate-900 hover:bg-black text-white shadow-sm transition">
                    <i data-lucide="trash-2" class="w-3 h-3"></i>
                    Hapus
                </button>
            </form>

        @endif
    </div>
</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="px-4 sm:px-6 py-3 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Prompt alasan untuk suspend / ban
    function handleUserStatusAction(form, type) {
        let label = type === 'suspend'
            ? 'suspend (nonaktif sementara)'
            : 'ban (blokir permanen)';

        const reason = prompt(`Tuliskan alasan untuk ${label} user ini (wajib diisi):`);
        if (!reason || !reason.trim()) {
            alert('Alasan wajib diisi untuk keperluan jejak audit.');
            return false;
        }

        const input = form.querySelector('input[name="reason"]');
        if (input) {
            input.value = reason.trim();
        }

        return true;
    }
</script>
@endpush
@endsection
