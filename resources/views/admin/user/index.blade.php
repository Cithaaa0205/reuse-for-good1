@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
@php
    // Kalau $users itu paginator, ambil collection-nya dulu
    $collection   = $users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->getCollection() : collect($users);
    $totalUsers   = $collection->count();
    $totalAdmins  = $collection->where('role', 'admin')->count();
    $totalDonatur = $collection->where('role', 'donatur')->count();
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
                Kelola akun donatur dan admin di Reuse For Good. Pastikan data pengguna tetap rapi dan aman.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="px-3 py-1.5 rounded-full text-[11px] font-medium bg-slate-900 text-sky-100 shadow-sm">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                    Akses Khusus Admin
                </span>
            </div>
        </div>
    </div>

    {{-- INFO & FILTER BAR --}}
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
                <i data-lucide="gift" class="w-3 h-3"></i>
                <span>{{ $totalDonatur }} donatur</span>
            </span>
        </div>

        {{-- Search sederhana (client-side) --}}
        <div class="w-full md:w-64">
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
    <div
        class="rounded-3xl bg-gradient-to-br from-slate-900/5 via-blue-100/10 to-sky-100/20 p-[1.5px] shadow-xl shadow-slate-200/60">
        <div class="overflow-hidden bg-white/95 backdrop-blur rounded-[1.4rem] border border-slate-100">
            <div class="px-4 sm:px-6 py-3 border-b border-slate-100 flex items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-900">
                    Daftar Pengguna
                </h2>
                <span class="text-[11px] text-slate-400">
                    Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="user-table" class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600">
                            <th class="px-4 py-3 text-left font-semibold">ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Username</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">No. Telepon</th>
                            <th class="px-4 py-3 text-left font-semibold">Role</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition"
                                data-row="{{ strtolower($user->nama_lengkap . ' ' . $user->username . ' ' . $user->email . ' ' . ($user->nomor_telepon ?? '')) }}">
                                <td class="px-4 py-3 text-slate-500">{{ $user->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="hidden sm:flex h-8 w-8 rounded-full bg-slate-100 text-slate-500 text-xs font-semibold items-center justify-center">
                                            {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->nama_lengkap), 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs sm:text-sm">{{ $user->nama_lengkap }}</p>
                                            <p class="text-[11px] text-slate-400 hidden sm:block">
                                                @<span>{{ $user->username }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:hidden">
                                    @<span>{{ $user->username }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $user->nomor_telepon ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold 
                                        {{ $user->role === 'admin' 
                                            ? 'bg-purple-50 text-purple-700 border border-purple-100' 
                                            : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                            {{ $user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                                  bg-amber-400 hover:bg-amber-500 text-white shadow-sm transition">
                                            <i data-lucide="edit-2" class="w-3 h-3 mr-1"></i>
                                            Edit
                                        </a>

                                        {{-- Jangan tampilkan tombol hapus untuk diri sendiri --}}
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                                               bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                                                    <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    Belum ada pengguna terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
