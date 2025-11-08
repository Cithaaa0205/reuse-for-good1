@extends('layouts.app')

@section('title', $barang->nama_barang)

@section('showBackButton', true)

@section('content')
<main class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Kolom Kiri: Gambar -->
        <div>
            <!-- === PERBAIKAN PATH GAMBAR === -->
            <img src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}" alt="{{ $barang->nama_barang }}" class="w-full h-auto object-cover rounded-2xl shadow-lg aspect-square">
            <!-- Galeri foto tambahan bisa ditambahkan di sini -->
        </div>

        <!-- Kolom Kanan: Info & Aksi -->
        <div class="flex flex-col gap-6">
            <!-- Info Utama -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-start mb-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $barang->nama_barang }}</h1>
                    <button class="text-gray-400 hover:text-red-500">
                        <i data-lucide="heart" class="w-6 h-6"></i>
                    </button>
                </div>
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mb-4">
                    {{ $barang->kategori->nama_kategori }}
                </span>
                <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                <p class="text-gray-700 mb-4">{{ $barang->deskripsi }}</p>
                <p class="text-sm text-gray-500">Kondisi: <span class="font-medium text-gray-800">{{ $barang->kondisi }}</span></p>
                <p class="text-sm text-gray-500 mt-1">Lokasi: <span class="font-medium text-gray-800">{{ $barang->lokasi }}</span></p>
            </div>

            <!-- Info Donatur (Sekarang bisa diklik) -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <a href="{{ route('profile.show', $barang->donatur->username) }}" class="flex items-center gap-4 group">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($barang->donatur->nama_lengkap) }}&background=E0F7FA&color=0284C7" alt="Avatar" class="w-14 h-14 rounded-full">
                    <div>
                        <h4 class="text-lg font-semibold group-hover:text-blue-600">{{ $barang->donatur->nama_lengkap }}</h4>
                        <p class="text-sm text-gray-600">4.8 &starf; | {{ $barang->donatur->barangDonasis()->count() }} donasi</p>
                    </div>
                </a>
            </div>

            <!-- Tombol Aksi -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                {{-- Tampilkan pesan success/error --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                     <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form action="{{ route('request.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="barang_donasi_id" value="{{ $barang->id }}">
                    
                    @if(Auth::id() == $barang->donatur_id)
                         <button type="button" disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                            Ini Barang Anda
                        </button>
                    @elseif($sudahDiajukan)
                        <button type="button" disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                            Sudah Diajukan
                        </button>
                    @elseif($barang->status == 'Tersedia')
                         <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                            Ajukan Penerimaan Barang
                        </button>
                    @else
                         <button type="button" disabled class="w-full bg-red-400 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed">
                            Barang Tidak Tersedia
                        </button>
                    @endif
                    
                </form>
                <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300 mt-3">
                    Hubungi Pendonasi
                </button>
            </div>
        </div>
    </div>

    <!-- Barang Serupa -->
    <div class="mt-12">
        <h3 class="text-2xl font-bold mb-4">Barang Serupa</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($barangSerupa as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <a href="{{ route('barang.show', $item->id) }}">
                        <!-- === PERBAIKAN PATH GAMBAR === -->
                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover">
                        <div class="p-3">
                            <h4 class="font-semibold truncate">{{ $item->nama_barang }}</h4>
                            <p class="text-sm text-gray-600 truncate">{{ $item->deskripsi }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $item->lokasi }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-600 col-span-full">Tidak ada barang serupa.</p>
            @endforelse
        </div>
    </div>
</main>
@endsection