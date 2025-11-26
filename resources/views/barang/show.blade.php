@extends('layouts.app')

@section('title', $barang->nama_barang)
@section('showBackButton', true)

@section('content')
<main class="max-w-7xl mx-auto py-10 px-4 lg:px-0">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- ================= Gambar Utama + Thumbnail ================= --}}
        <div class="flex flex-col items-center">
            <img id="mainImage"
                src="{{ $barang->foto_barang_utama ? asset('uploads/barang/' . $barang->foto_barang_utama) : 'https://placehold.co/800x600?text=No+Image' }}"
                alt="{{ $barang->nama_barang }}"
                class="w-full rounded-2xl shadow-xl object-cover aspect-square bg-gray-100">

            @php
                $fotoLain = $barang->foto_barang_lainnya ? json_decode($barang->foto_barang_lainnya) : [];
            @endphp

            @if(count($fotoLain) > 0)
                <div class="flex gap-3 mt-4 overflow-x-auto pb-1">
                    <img src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                         class="thumb w-24 h-24 rounded-xl border-2 border-blue-600 cursor-pointer object-cover">

                    @foreach($fotoLain as $foto)
                        <img src="{{ asset('uploads/barang/' . $foto) }}"
                             class="thumb w-24 h-24 rounded-xl border cursor-pointer object-cover">
                    @endforeach
                </div>
            @endif
        </div>


        {{-- ================= DETAIL BARANG ================= --}}
        <div class="flex flex-col gap-6">

            <div class="bg-white rounded-2xl shadow-md p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>

                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-1 rounded-full">
                    {{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                </span>

                <h3 class="text-lg font-semibold mt-5">Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed">{{ $barang->deskripsi }}</p>

                <p class="text-sm text-gray-600 mt-3">Kondisi:
                    <span class="font-semibold text-gray-900">{{ $barang->kondisi }}</span>
                </p>

                <p class="text-sm text-gray-600 mt-1">Lokasi:
                    <span class="font-semibold text-gray-900">
                        {{ $barang->kabupaten ?? '-' }}, {{ $barang->provinsi ?? '-' }}
                    </span>
                </p>
            </div>


            {{-- ================= INFO DONATUR ================= --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <a href="{{ $barang->donatur ? route('profile.show', $barang->donatur->username) : '#' }}"
                   class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($barang->donatur->nama_lengkap ?? 'User', 0, 2)) }}
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold">
                            {{ $barang->donatur->nama_lengkap ?? 'Pengguna Tidak Diketahui' }}
                        </h4>
                        <p class="text-sm text-gray-600">
                            {{ $barang->donatur ? $barang->donatur->barangDonasis->count() : 0 }} Donasi
                        </p>
                    </div>
                </a>
            </div>


            {{-- ================= TOMBOL AKSI ================= --}}
            <div class="bg-white rounded-2xl shadow-md p-6">

                @if(Auth::check())
                    @if(Auth::id() == $barang->donatur_id)
                        {{-- Pendonasi --}}
                        <p class="text-center text-gray-700 mb-4">Ini adalah donasi Anda.</p>

                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?');">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl">
                                Hapus Donasi Ini
                            </button>
                        </form>

                    @else
                        {{-- Penerima --}}
                        @if($sudahDiajukan)
                            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-xl text-center font-semibold mb-3">
                                Permintaan sudah diajukan. Menunggu konfirmasi pendonasi.
                            </div>

                            <a href="{{ route('chat.show', $barang->donatur->id) }}"
                               class="block text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl">
                                Hubungi Pendonasi
                            </a>

                        @elseif($barang->status === 'Tersedia')
                            <form action="{{ route('request.store', $barang->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2">
                                    Ajukan Penerimaan Barang
                                </button>
                            </form>

                            <a href="{{ route('chat.show', $barang->donatur->id) }}"
                                class="block text-center mt-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl">
                                Hubungi Pendonasi
                            </a>

                        @else
                            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-xl text-center font-semibold">
                                Barang ini telah diterima.
                            </div>
                        @endif
                    @endif

                @else
                    <a href="{{ route('login') }}"
                       class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl">
                       Login untuk Mengajukan
                    </a>
                @endif

            </div>

        </div>
    </div>
</main>


{{-- ========== SCRIPT GANTI FOTO THUMBNAIL ========== --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const mainImg = document.getElementById("mainImage");
    const thumbs = document.querySelectorAll(".thumb");

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
