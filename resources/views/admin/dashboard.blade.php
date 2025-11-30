{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $totalStatusRequests = $requestsDiajukan + $requestsDisetujui + $requestsDitolak;
    $percentApproved = $totalStatusRequests ? round(($requestsDisetujui / $totalStatusRequests) * 100) : 0;
    $percentRejected = $totalStatusRequests ? round(($requestsDitolak / $totalStatusRequests) * 100) : 0;
    $percentPending  = $totalStatusRequests ? round(($requestsDiajukan / $totalStatusRequests) * 100) : 0;
@endphp

<div class="space-y-8">
    {{-- HEADER + INFO RINGKAS --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-600">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                </span>
                Admin Dashboard
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan aktivitas platform Reuse For Good, hanya untuk admin.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs md:text-sm text-slate-600 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Data realtime berdasarkan aktivitas terbaru.
            </span>
        </div>
    </div>

    {{-- KARTU STATISTIK UTAMA --}}
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        {{-- Total User --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-blue-50 shadow-md border border-blue-100/80">
            <div class="relative z-10 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Total User
                    </p>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600/10 text-blue-600">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                    {{ $totalUsers }}
                </p>
                <p class="mt-1 text-xs text-slate-600">
                    Semua akun terdaftar di sistem.
                </p>
            </div>
            <div class="absolute -right-8 -bottom-8 h-24 w-24 rounded-full bg-blue-100/60"></div>
        </div>

        {{-- Total Barang --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-emerald-50 shadow-md border border-emerald-100/80">
            <div class="relative z-10 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Barang Didonasikan
                    </p>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600">
                        <i data-lucide="gift" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                    {{ $totalBarang }}
                </p>
                <div class="mt-2 flex items-center justify-between text-[11px] text-slate-700">
                    <span>Tersedia: <span class="font-semibold">{{ $barangTersedia }}</span></span>
                    <span>Dipesan: <span class="font-semibold">{{ $barangDipesan }}</span></span>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 h-24 w-24 rounded-full bg-emerald-100/70"></div>
        </div>

        {{-- Total Request --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-amber-50 shadow-md border border-amber-100/80">
            <div class="relative z-10 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Permintaan Barang
                    </p>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500">
                        <i data-lucide="inbox" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-slate-900">
                    {{ $totalRequests }}
                </p>
                <div class="mt-2 grid grid-cols-3 gap-1 text-[11px] text-slate-700">
                    <span>Diajukan: <span class="font-semibold">{{ $requestsDiajukan }}</span></span>
                    <span>Disetujui: <span class="font-semibold text-emerald-700">{{ $requestsDisetujui }}</span></span>
                    <span>Ditolak: <span class="font-semibold text-rose-600">{{ $requestsDitolak }}</span></span>
                </div>
            </div>
            <div class="absolute -right-10 -top-10 h-24 w-24 rounded-full bg-amber-100/70"></div>
        </div>

        {{-- Peringatan Ringkas --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 text-slate-50 shadow-lg border border-slate-900/70">
            <div class="relative z-10 p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-300">
                        Peringatan Sistem
                    </p>
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-red-500/15 text-red-400">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-extrabold">
                    {{ $oldAvailableItemsCount + $usersWithoutLocationCount }} isu
                </p>
                <div class="mt-2 space-y-1.5 text-[11px]">
                    <p>
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-amber-400 mr-1.5 align-middle"></span>
                        Barang tersedia &gt; 20 hari:
                        <span class="font-semibold">{{ $oldAvailableItemsCount }}</span>
                    </p>
                    <p>
                        <span class="inline-flex h-1.5 w-1.5 rounded-full bg-sky-400 mr-1.5 align-middle"></span>
                        User tanpa lokasi:
                        <span class="font-semibold">{{ $usersWithoutLocationCount }}</span>
                    </p>
                </div>
            </div>
            <div class="absolute -right-16 -bottom-10 h-32 w-32 rounded-full bg-slate-800/80"></div>
        </div>
    </div>

    {{-- BARIS 2: ANALITIK & KOTA --}}
    <div class="grid gap-6 xl:grid-cols-3">
        {{-- Distribusi status permintaan --}}
        <div class="xl:col-span-1 rounded-2xl bg-gradient-to-br from-white via-slate-50 to-emerald-50/40 shadow-md border border-emerald-100/80 p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                        <i data-lucide="activity" class="w-3 h-3"></i>
                    </span>
                    Kinerja Permintaan
                </h2>
                <span class="text-[11px] text-slate-500">
                    Total: {{ $totalStatusRequests }} permintaan
                </span>
            </div>

            @if($totalStatusRequests === 0)
                <p class="text-sm text-slate-600">Belum ada data permintaan.</p>
            @else
                <div class="space-y-3">
                    {{-- Bar gabungan --}}
                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 flex shadow-inner">
                        <div class="h-full bg-amber-400" style="width: {{ $percentPending }}%"></div>
                        <div class="h-full bg-emerald-500" style="width: {{ $percentApproved }}%"></div>
                        <div class="h-full bg-rose-500" style="width: {{ $percentRejected }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-[11px]">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                <span class="font-semibold text-slate-700">Diajukan</span>
                            </div>
                            <p class="text-slate-600">
                                {{ $percentPending }}% ({{ $requestsDiajukan }})
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="font-semibold text-slate-700">Disetujui</span>
                            </div>
                            <p class="text-slate-600">
                                {{ $percentApproved }}% ({{ $requestsDisetujui }})
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-1">
                                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                <span class="font-semibold text-slate-700">Ditolak</span>
                            </div>
                            <p class="text-slate-600">
                                {{ $percentRejected }}% ({{ $requestsDitolak }})
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Top kota barang & user --}}
        <div class="xl:col-span-2 grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-blue-50/40 shadow-md border border-blue-100 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                        </span>
                        Top 5 Kota (Barang)
                    </h2>
                </div>
                @if($topKotaByBarang->isEmpty())
                    <p class="text-sm text-slate-600">Belum ada data barang.</p>
                @else
                    <div class="space-y-2">
                        @foreach($topKotaByBarang as $index => $kota)
                            <div class="flex items-center justify-between rounded-xl bg-white/60 px-3 py-2 text-xs shadow-sm border border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-50 text-[11px] font-semibold text-slate-600 border border-slate-200">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $kota->kabupaten ?? '-' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500">
                                            Barang donasi: {{ $kota->total }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-sky-50/40 shadow-md border border-sky-100 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                            <i data-lucide="users" class="w-3 h-3"></i>
                        </span>
                        Top 5 Kota (User)
                    </h2>
                </div>
                @if($topKotaByUser->isEmpty())
                    <p class="text-sm text-slate-600">Belum ada data user.</p>
                @else
                    <div class="space-y-2">
                        @foreach($topKotaByUser as $index => $kota)
                            <div class="flex items-center justify-between rounded-xl bg-white/60 px-3 py-2 text-xs shadow-sm border border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-slate-50 text-[11px] font-semibold text-slate-600 border border-slate-200">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $kota->kabupaten ?? '-' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500">
                                            User terdaftar: {{ $kota->total }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BARIS 3: AKTIVITAS TERBARU --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Donasi Terbaru --}}
        <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-emerald-50/30 shadow-md border border-emerald-100 flex flex-col">
            <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-emerald-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <i data-lucide="package-plus" class="w-3 h-3"></i>
                    </span>
                    5 Donasi Terbaru
                </h2>
            </div>
            <div class="p-4">
                @if($latestBarang->isEmpty())
                    <p class="text-sm text-slate-600">Belum ada donasi.</p>
                @else
                    <ul class="space-y-3 text-sm max-h-72 overflow-y-auto">
                        @foreach($latestBarang as $barang)
                            <li class="border-b border-slate-100 pb-2 last:border-none last:pb-0">
                                <p class="font-semibold text-slate-800">{{ $barang->nama_barang }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Oleh: {{ optional($barang->donatur)->nama_lengkap ?? '-' }} ·
                                    Kota: {{ $barang->kabupaten ?? '-' }}<br>
                                    Tanggal:
                                    @if($barang->created_at)
                                        {{ $barang->created_at->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Request Terbaru --}}
        <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-amber-50/30 shadow-md border border-amber-100 flex flex-col">
            <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-amber-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
                        <i data-lucide="hand-heart" class="w-3 h-3"></i>
                    </span>
                    5 Request Terbaru
                </h2>
            </div>
            <div class="p-4">
                @if($latestRequests->isEmpty())
                    <p class="text-sm text-slate-600">Belum ada request.</p>
                @else
                    <ul class="space-y-3 text-sm max-h-72 overflow-y-auto">
                        @foreach($latestRequests as $req)
                            <li class="border-b border-slate-100 pb-2 last:border-none last:pb-0">
                                <p class="font-semibold text-slate-800">
                                    {{ optional($req->barangDonasi)->nama_barang ?? '-' }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Penerima: {{ optional($req->penerima)->nama_lengkap ?? '-' }}<br>
                                    Status:
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                        @if($req->status === 'Disetujui')
                                            bg-emerald-50 text-emerald-600
                                        @elseif($req->status === 'Ditolak')
                                            bg-rose-50 text-rose-600
                                        @else
                                            bg-amber-50 text-amber-600
                                        @endif">
                                        {{ $req->status }}
                                    </span><br>
                                    Tanggal:
                                    @if($req->created_at)
                                        {{ $req->created_at->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- User Terbaru --}}
        <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-sky-50/30 shadow-md border border-sky-100 flex flex-col">
            <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-sky-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-sky-50 text-sky-600">
                        <i data-lucide="user-plus" class="w-3 h-3"></i>
                    </span>
                    5 User Baru
                </h2>
            </div>
            <div class="p-4">
                @if($latestUsers->isEmpty())
                    <p class="text-sm text-slate-600">Belum ada user.</p>
                @else
                    <ul class="space-y-3 text-sm max-h-72 overflow-y-auto">
                        @foreach($latestUsers as $user)
                            <li class="border-b border-slate-100 pb-2 last:border-none last:pb-0">
                                <p class="font-semibold text-slate-800">
                                    {{ $user->nama_lengkap ?? $user->username }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Email: {{ $user->email }}<br>
                                    Kota: {{ $user->kabupaten ?? '-' }}<br>
                                    Daftar:
                                    @if($user->created_at)
                                        {{ $user->created_at->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- BARIS 4: DETAIL PERINGATAN --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Barang lama --}}
        <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-orange-50/30 shadow-md border border-orange-100 flex flex-col">
            <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-orange-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-orange-50 text-orange-500">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                    </span>
                    Barang "Tersedia" &gt; 20 hari
                </h2>
                <span class="text-[11px] text-slate-500">
                    {{ $oldAvailableItemsCount }} barang
                </span>
            </div>
            <div class="p-4">
                @if($oldAvailableItems->isEmpty())
                    <p class="text-sm text-slate-600">
                        Tidak ada barang yang terlalu lama tersedia. Good job! 🎉
                    </p>
                @else
                    <ul class="space-y-2 text-sm max-h-72 overflow-y-auto">
                        @foreach($oldAvailableItems as $barang)
                            <li class="border-b border-slate-100 pb-2 last:border-none last:pb-0">
                                <p class="font-semibold text-slate-800">{{ $barang->nama_barang }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Kota: {{ $barang->kabupaten ?? '-' }}<br>
                                    Tersedia sejak:
                                    @if($barang->created_at)
                                        {{ $barang->created_at->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- User tanpa lokasi --}}
        <div class="rounded-2xl bg-gradient-to-br from-white via-slate-50 to-rose-50/30 shadow-md border border-rose-100 flex flex-col">
            <div class="flex items-center justify-between px-4 pt-4 pb-3 border-b border-rose-50">
                <h2 class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-rose-50 text-rose-500">
                        <i data-lucide="map-off" class="w-3 h-3"></i>
                    </span>
                    User Tanpa Lokasi
                </h2>
                <span class="text-[11px] text-slate-500">
                    {{ $usersWithoutLocationCount }} user
                </span>
            </div>
            <div class="p-4">
                @if($usersWithoutLocation->isEmpty())
                    <p class="text-sm text-slate-600">
                        Semua user sudah mengisi lokasi. 🎯
                    </p>
                @else
                    <ul class="space-y-2 text-sm max-h-72 overflow-y-auto">
                        @foreach($usersWithoutLocation as $user)
                            <li class="border-b border-slate-100 pb-2 last:border-none last:pb-0">
                                <p class="font-semibold text-slate-800">{{ $user->nama_lengkap ?? $user->username }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Email: {{ $user->email }}<br>
                                    Terdaftar:
                                    @if($user->created_at)
                                        {{ $user->created_at->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
