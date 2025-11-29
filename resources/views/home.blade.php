@extends('layouts.app')

@section('title', 'Beranda - Reuse For Good')

@section('content')
    {{-- Banner / Hero --}}
    <section class="mb-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-lg">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,_#ffffff,_transparent_60%)]"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 px-6 sm:px-8 py-7 relative z-10">
                <div class="space-y-3 max-w-xl">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 text-xs font-medium backdrop-blur">
                        <i data-lucide="leaf" class="w-4 h-4"></i>
                        Donasi barang bekas, bantu sesama & bumi 🌱
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                        Mari <span class="underline decoration-white/60">Donasi</span> Barang Bekas
                        <br class="hidden sm:block" />
                        Jadi Manfaat Untuk Orang Lain.
                    </h1>
                    <p class="text-sm sm:text-base text-blue-50/90">
                        Donasikan barang bekas layak pakai, bantu mereka yang membutuhkan, kurangi sampah,
                        dan buat bumi tersenyum. Reuse For Good menghubungkan donatur dan penerima secara mudah dan gratis.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('barang.create') }}" 
                           class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full bg-white text-blue-700 text-sm font-semibold shadow-md hover:bg-blue-50 transition">
                            <i data-lucide="gift" class="w-4 h-4"></i>
                            Donasikan Barang
                        </a>
                        <a href="{{ route('barang.index') }}" 
                           class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full border border-white/60 text-sm font-medium text-white hover:bg-white/10 transition">
                            <i data-lucide="compass" class="w-4 h-4"></i>
                            Mulai Jelajahi
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex flex-col items-end gap-3">
                    <div class="rounded-2xl bg-white/10 backdrop-blur px-4 py-3 text-right shadow-md">
                        <p class="text-xs text-blue-50/90">Statistik singkat</p>
                        <p class="text-2xl font-bold">
                            {{ number_format($stats['barang_didonasikan']) }}+
                        </p>
                        <p class="text-[11px] text-blue-100">Barang sudah didonasikan</p>
                    </div>
                    <a href="{{ route('barang.index') }}" 
                       class="hidden md:inline-flex items-center gap-2 bg-white/90 text-blue-700 font-semibold py-2.5 px-5 rounded-full shadow-md hover:bg-white transition text-sm">
                        Mulai Jelajahi
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Grid Aksi Utama --}}
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <a href="{{ route('barang.create') }}" 
           class="group bg-white/95 backdrop-blur rounded-3xl border border-slate-200/90 ring-1 ring-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 hover:border-blue-200 transition overflow-hidden">
            <div class="p-5 flex flex-col items-center text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition">
                    <i data-lucide="gift" class="w-7 h-7 text-blue-500"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Donasikan Barang</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Berbagi barang layak pakai untuk membantu sesama yang membutuhkan.
                    </p>
                </div>
                <span class="mt-1 inline-flex items-center text-xs font-medium text-blue-600 group-hover:translate-x-1 transition">
                    Mulai <span aria-hidden="true" class="ml-1">&rarr;</span>
                </span>
            </div>
        </a>

        <a href="{{ route('barang.index') }}" 
           class="group bg-white/95 backdrop-blur rounded-3xl border border-slate-200/90 ring-1 ring-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 hover:border-emerald-200 transition overflow-hidden">
            <div class="p-5 flex flex-col items-center text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <i data-lucide="package-check" class="w-7 h-7 text-emerald-500"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Terima Barang</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Ajukan permintaan dan terima barang sesuai kebutuhan, tanpa biaya.
                    </p>
                </div>
                <span class="mt-1 inline-flex items-center text-xs font-medium text-emerald-600 group-hover:translate-x-1 transition">
                    Mulai <span aria-hidden="true" class="ml-1">&rarr;</span>
                </span>
            </div>
        </a>

        <a href="{{ route('chat.index') }}" 
           class="group bg-white/95 backdrop-blur rounded-3xl border border-slate-200/90 ring-1 ring-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 hover:border-purple-200 transition overflow-hidden">
            <div class="p-5 flex flex-col items-center text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition">
                    <i data-lucide="messages-square" class="w-7 h-7 text-purple-500"></i>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Chat Room</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Berkomunikasi langsung dengan donatur atau penerima untuk koordinasi.
                    </p>
                </div>
                <span class="mt-1 inline-flex items-center text-xs font-medium text-purple-600 group-hover:translate-x-1 transition">
                    Mulai <span aria-hidden="true" class="ml-1">&rarr;</span>
                </span>
            </div>
        </a>
    </section>

    {{-- Grid Statistik --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/90 rounded-2xl border border-slate-100 shadow-sm p-4 text-center flex flex-col items-center gap-1">
            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center mb-1">
                <i data-lucide="package" class="w-5 h-5 text-slate-500"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ number_format($stats['barang_didonasikan']) }}
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Barang Didonasikan</p>
        </div>

        <div class="bg-white/90 rounded-2xl border border-slate-100 shadow-sm p-4 text-center flex flex-col items-center gap-1">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center mb-1">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ number_format($stats['barang_diterima']) }}
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Barang Diterima</p>
        </div>

        <div class="bg-white/90 rounded-2xl border border-slate-100 shadow-sm p-4 text-center flex flex-col items-center gap-1">
            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center mb-1">
                <i data-lucide="users" class="w-5 h-5 text-slate-500"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ number_format($stats['pengguna_aktif']) }}
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Pengguna Aktif</p>
        </div>

        <div class="bg-white/90 rounded-2xl border border-slate-100 shadow-sm p-4 text-center flex flex-col items-center gap-1">
            <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center mb-1">
                <i data-lucide="map-pin" class="w-5 h-5 text-slate-500"></i>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ $stats['kota'] }}
            </div>
            <p class="text-xs sm:text-sm text-slate-500">Kota</p>
        </div>
    </section>

    {{-- Barang Terbaru --}}
    <section>
        <div class="flex items-center justify-between mb-4 gap-2">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold text-slate-900">Barang Terbaru di Sekitar Anda</h3>
                <p class="text-xs sm:text-sm text-slate-500">
                    Lihat barang yang baru saja didonasikan dan masih tersedia.
                </p>
            </div>
            <a href="{{ route('barang.index') }}" 
               class="hidden sm:inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-700">
                Lihat semua
                <i data-lucide="arrow-right" class="w-3 h-3 ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($barangTerbaru as $item)
                @php
                    // Format lokasi: "Kabupaten, Provinsi" contoh: "Sleman, DI Yogyakarta"
                    $namaKabupaten = $item->kabupaten ?? '-';
                    $namaProvinsi = $item->provinsi ?? '-';
                    $lokasiDisplay = $namaKabupaten . ', ' . $namaProvinsi;
                @endphp

                <div class="relative">
                    {{-- Kartu utama --}}
                    <a href="{{ route('barang.show', $item->id) }}"
                       class="block bg-white/95 rounded-3xl border border-slate-100 shadow-[0_18px_40px_rgba(15,23,42,0.06)]
                              hover:shadow-[0_24px_55px_rgba(15,23,42,0.10)] hover:-translate-y-1 transition overflow-hidden">

                        {{-- Gambar --}}
                        <div class="relative">
                            @if($item->foto_barang_utama)
                                <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" 
                                     alt="{{ $item->nama_barang }}" 
                                     class="w-full h-32 md:h-40 object-cover">
                            @else
                                <div class="w-full h-32 md:h-40 bg-slate-100 flex items-center justify-center">
                                    <i data-lucide="image-off" class="w-8 h-8 text-slate-400"></i>
                                </div>
                            @endif

                            {{-- gradient overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>

                            {{-- badge kategori --}}
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold
                                               bg-white/90 text-slate-800 border border-slate-200 shadow-sm">
                                    {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="p-3 md:p-4 flex flex-col gap-1">
                            <h4 class="font-semibold text-xs sm:text-sm md:text-base text-slate-900 truncate">
                                {{ $item->nama_barang }}
                            </h4>

                            {{-- Lokasi: Kab, Prov --}}
                            <p class="text-[11px] md:text-xs text-slate-500 flex items-center gap-1 mt-1">
                                <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                                <span class="truncate">{{ $lokasiDisplay }}</span>
                            </p>

                            {{-- Waktu + badge Donasi --}}
                            <div class="mt-2 flex items-center justify-between gap-2 text-[11px] md:text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-600">
                                    <i data-lucide="recycle" class="w-3 h-3"></i>
                                    <span>Donasi</span>
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- Tombol Favorit --}}
                    @auth
                        @php $isFavorited = in_array($item->id, $favoriteIds); @endphp
                        <form action="{{ route('favorite.toggle', $item->id) }}" method="POST" 
                              class="absolute top-2 right-2 z-20">
                            @csrf
                            <button type="submit" 
                                    class="favorite-btn p-2 rounded-full bg-white/90 hover:bg-white text-slate-500 hover:text-red-500 shadow-md transition {{ $isFavorited ? 'favorited text-red-500' : '' }}">
                                <i data-lucide="heart" class="w-5 h-5 icon-outline"></i>
                                <i data-lucide="heart" class="w-5 h-5 icon-filled fill-current"></i>
                            </button>
                        </form>
                    @endauth
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white/90 rounded-3xl border border-slate-100 shadow-sm p-8 text-center">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto text-slate-300 mb-3"></i>
                        <p class="text-sm font-semibold text-slate-700">Belum ada barang yang didonasikan.</p>
                        <p class="text-xs text-slate-500 mt-1">
                            Jadilah yang pertama untuk berbagi kebaikan dengan mendonasikan barang layak pakai.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection
