@extends('layouts.app')

@section('title', $barang->nama_barang)
@section('showBackButton', true)

@section('content')
@php
    // Susun lokasi seperti contoh: (Jawa Tengah, Surakarta)
    $namaProvinsi  = $barang->provinsi ?? '-';
    $namaKabupaten = $barang->kabupaten ?? '-';
    $lokasiDisplay = $namaProvinsi . ', ' . $namaKabupaten;

    // Decode foto lain
    $fotoLain = $barang->foto_barang_lainnya ? json_decode($barang->foto_barang_lainnya, true) : [];

    // Path gambar utama (kalau ada)
    $mainImageSrc = $barang->foto_barang_utama
        ? asset('uploads/barang/' . $barang->foto_barang_utama)
        : 'https://placehold.co/800x600?text=No+Image';
@endphp

<main class="max-w-7xl mx-auto space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400 mb-1">Detail Barang Donasi</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $barang->nama_barang }}</h1>

            <div class="mt-2 flex flex-wrap gap-2 items-center text-[11px] text-slate-500">
                {{-- Kategori --}}
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold">
                    <i data-lucide="tag" class="w-3 h-3"></i>
                    {{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                </span>

                {{-- Lokasi (Provinsi, Kabupaten) --}}
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    {{ $lokasiDisplay }}
                </span>

                {{-- Waktu posting --}}
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    Diposting {{ $barang->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-8 items-start">

        {{-- ================= Gambar ================= --}}
        <div class="space-y-4">
            {{-- Gambar utama --}}
            <div class="relative rounded-3xl overflow-hidden bg-slate-100 shadow-[0_22px_55px_rgba(15,23,42,0.15)]">
                <img
                    id="mainImage"
                    src="{{ $mainImageSrc }}"
                    alt="{{ $barang->nama_barang }}"
                    class="w-full aspect-[4/3] object-cover"
                    onerror="this.onerror=null;this.src='https://placehold.co/800x600?text=No+Image';"
                >

                {{-- Overlay gradient halus --}}
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

                {{-- Badge status --}}
                <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-semibold
                                 {{ $barang->status === 'Tersedia'
                                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                        : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                        {{ $barang->status ?? 'Tersedia' }}
                    </span>
                </div>

                {{-- Lokasi di atas gambar --}}
                <div class="absolute bottom-3 left-3">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-medium
                                 bg-white/95 text-slate-800 border border-slate-200 shadow-sm">
                        <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                        {{ $lokasiDisplay }}
                    </span>
                </div>
            </div>

            {{-- Thumbnail --}}
            @if($barang->foto_barang_utama || count($fotoLain) > 0)
                <div class="flex gap-3 mt-1 overflow-x-auto pb-1">
                    @if($barang->foto_barang_utama)
                        <img
                            src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                            class="thumb w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border-2 border-blue-600 cursor-pointer object-cover bg-slate-100"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';"
                        >
                    @endif

                    @foreach($fotoLain as $foto)
                        <img
                            src="{{ asset('uploads/barang/' . $foto) }}"
                            class="thumb w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border cursor-pointer object-cover bg-slate-100 hover:border-blue-300 transition"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';"
                        >
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ================= DETAIL & AKSI ================= --}}
        <div class="space-y-5">

            {{-- Detail barang --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 space-y-4">
                <div class="space-y-2 text-sm text-slate-700">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">
                        Deskripsi
                    </h3>
                    <p class="leading-relaxed">
                        {{ $barang->deskripsi }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Kondisi</p>
                        <p class="font-semibold text-slate-900">
                            {{ $barang->kondisi ?? '-' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Lokasi</p>
                        <p class="font-semibold text-slate-900 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-4 h-4 text-rose-500"></i>
                            <span>{{ $lokasiDisplay }}</span>
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Jenis Donasi</p>
                        <p class="font-semibold text-slate-900 flex items-center gap-1">
                            <i data-lucide="recycle" class="w-4 h-4 text-emerald-500"></i>
                            <span>Barang Bekas Layak Pakai</span>
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">ID Donasi</p>
                        <p class="font-mono text-xs text-slate-600">
                            #BRG{{ str_pad($barang->id, 5, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Donatur --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ $barang->donatur ? route('profile.show', $barang->donatur->username) : '#' }}"
                       class="flex items-center gap-4 flex-1">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-600 to-sky-500
                                    flex items-center justify-center text-white font-bold text-sm sm:text-lg">
                            {{ strtoupper(substr($barang->donatur->nama_lengkap ?? 'User', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $barang->donatur->nama_lengkap ?? 'Pengguna Tidak Diketahui' }}
                            </p>
                            <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                <i data-lucide="gift" class="w-3 h-3"></i>
                                <span>{{ $barang->donatur ? $barang->donatur->barangDonasis->count() : 0 }} donasi telah dibuat</span>
                            </p>
                        </div>
                    </a>
                </div>

                @auth
                    @if(Auth::id() !== $barang->donatur_id)
                        <a href="{{ route('chat.show', $barang->donatur->id) }}"
                           class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-2xl text-sm font-semibold
                                  bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            Hubungi Pendonasi
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Tombol aksi / status permintaan --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 space-y-3">
                @if(Auth::check())
                    {{-- Jika pendonasi --}}
                    @if(Auth::id() == $barang->donatur_id)
                        <div class="text-center space-y-3">
                            <p class="text-sm text-slate-600">
                                Ini adalah donasi milik kamu. Kamu bisa menghapusnya jika sudah tidak tersedia.
                            </p>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                               bg-red-600 text-white hover:bg-red-700 shadow-md">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                    Hapus Donasi Ini
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Bukan pendonasi --}}
                        @if($requestStatus === 'Diajukan')
                            <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-amber-800 flex gap-2">
                                <i data-lucide="hourglass" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Permintaan penerimaan barang sudah diajukan dan sedang menunggu konfirmasi dari pendonasi.
                                </p>
                            </div>
                        @elseif($requestStatus === 'Ditolak')
                            <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-red-700 flex gap-2">
                                <i data-lucide="x-circle" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Permintaan sebelumnya ditolak. Jika masih membutuhkan barang ini, silakan ajukan kembali.
                                </p>
                            </div>

                            <form action="{{ route('request.store', $barang->id) }}" method="POST" class="pt-1">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                               bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                                    <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i>
                                    Ajukan Ulang Permintaan
                                </button>
                            </form>
                        @elseif($barang->status !== 'Tersedia')
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-emerald-800 flex gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Barang ini sudah diterima oleh penerima. Terima kasih sudah mengecek 💚
                                </p>
                            </div>
                        @else
                            <form action="{{ route('request.store', $barang->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                               bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                                    <i data-lucide="hand-heart" class="w-4 h-4 mr-2"></i>
                                    Ajukan Penerimaan Barang
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    {{-- Belum login --}}
                    <div class="space-y-3 text-center">
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-blue-800">
                            Masuk terlebih dahulu untuk mengajukan penerimaan barang.
                        </div>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                  bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                            <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                            Login untuk Mengajukan
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</main>

{{-- SCRIPT GANTI GAMBAR UTAMA --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const mainImg = document.getElementById("mainImage");
    const thumbs  = document.querySelectorAll(".thumb");

    thumbs.forEach(t => {
        t.addEventListener("click", () => {
            mainImg.src = t.src;
            thumbs.forEach(x => x.classList.remove("border-blue-600"));
            t.classList.add("border-blue-600");
        });
    });
});
</script>
@endsection
