@extends('layouts.app')

@section('title', $barang->nama_barang)
@section('showBackButton', true)

@section('content')
<main class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Kolom Kiri: Gambar + Thumbnail -->
        <div class="relative z-0 flex flex-col items-center">
            <img id="mainImage"
                src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                alt="{{ $barang->nama_barang }}"
                class="w-full h-auto object-cover rounded-2xl shadow-lg aspect-square">

            @php
                $fotoLain = $barang->foto_barang_lainnya ? json_decode($barang->foto_barang_lainnya) : [];
            @endphp

            @if($fotoLain)
            <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                <!-- Thumbnail utama -->
                <img src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                    class="thumb w-24 h-24 rounded-xl border-2 border-blue-500 cursor-pointer object-cover">

                <!-- Thumbnail lainnya -->
                @foreach($fotoLain as $foto)
                    <img src="{{ asset('uploads/barang/' . $foto) }}"
                        class="thumb w-24 h-24 rounded-xl border cursor-pointer object-cover">
                @endforeach
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Informasi Barang -->
        <div class="flex flex-col gap-6">

            <div class="bg-white p-6 rounded-2xl shadow-md relative">
                <div class="flex justify-between items-start">
                    <h1 class="text-3xl font-bold">{{ $barang->nama_barang }}</h1>
                </div>

                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mt-3">
                    {{ $barang->kategori->nama_kategori }}
                </span>

                <h3 class="text-lg font-semibold mt-4">Deskripsi</h3>
                <p class="text-gray-700">{{ $barang->deskripsi }}</p>

                <p class="text-sm text-gray-500 mt-2">Kondisi: 
                    <span class="font-semibold text-gray-800">{{ $barang->kondisi }}</span>
                </p>

                <p class="text-sm text-gray-500 mt-1">Lokasi: 
                    <span class="font-semibold text-gray-800">
                        {{ $barang->kabupaten }}, {{ $barang->provinsi }}
                    </span>
                </p>
            </div>

            <!-- INFO DONATUR -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <a href="{{ route('profile.show', $barang->donatur->username) }}" class="flex items-center gap-4 group">
                    @if($barang->donatur->foto_profil)
                        <img class="h-14 w-14 rounded-full object-cover"
                             src="{{ asset('uploads/avatars/' . $barang->donatur->foto_profil) }}" alt="Avatar">
                    @else
                        <div class="h-14 w-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($barang->donatur->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-semibold group-hover:text-blue-600 transition">{{ $barang->donatur->nama_lengkap }}</h4>
                        <p class="text-sm text-gray-600">{{ $barang->donatur->barangDonasis->count() }} Donasi</p>
                    </div>
                </a>
            </div>

            <!-- TOMBOL AKSI -->
<div class="bg-white p-6 rounded-2xl shadow-md">
    @if(Auth::check())
        @if(Auth::id() == $barang->donatur_id)
            <p class="text-center text-gray-600 mb-3">Ini adalah donasi Anda.</p>

            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg">
                    Hapus Donasi Ini
                </button>
            </form>

        @else
            @if($barang->status === 'Tersedia')
                <form action="{{ route('request.store', $barang->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2">
                        <i data-lucide="hand-heart" class="w-5 h-5"></i>
                        Ajukan Penerimaan Barang
                    </button>
                </form>

                <a href="{{ route('chat.show', $barang->donatur->id) }}"
                    class="block text-center mt-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg flex items-center justify-center gap-2">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Hubungi Pendonasi
                </a>
            @else
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center font-semibold flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Barang ini telah diterima.
                </div>
            @endif
        @endif
    @else
        <a href="{{ route('login') }}"
            class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg">
            Login untuk Mengajukan
        </a>
    @endif
</div>

        </div>
    </div>
</main>

{{-- SCRIPT THUMBNAIL --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const mainImage = document.getElementById("mainImage");
    const thumbnails = document.querySelectorAll(".thumb");

    thumbnails.forEach(thumb => {
        thumb.addEventListener("click", () => {
            thumbnails.forEach(t => t.classList.remove("border-blue-500"));
            thumb.classList.add("border-blue-500");
            mainImage.src = thumb.src;
        });
    });
});
</script>

@endsection
