@extends('layouts.app')

@section('title', 'Beranda - Reuse For Good')

@section('content')
    <!-- Banner Selamat Datang -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-400 text-white p-8 rounded-2xl shadow-lg mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold mb-2">Mari Donasi!</h1>
            <p class="text-lg max-w-lg">
                Donasikan barang bekas layak pakai, bantu mereka yang membutuhkan, kurangi sampah, dan buat bumi tersenyum.
            </p>
        </div>
        <a href="{{ route('barang.index') }}" class="bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-6 rounded-lg transition duration-300 hidden md:inline-block">
            Mulai Jelajahi
        </a>
    </div>

    <!-- Grid Aksi Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Donasikan Barang -->
        <a href="{{ route('barang.create') }}" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
            <i data-lucide="gift" class="w-16 h-16 text-blue-500 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold mb-2">DONASIKAN BARANG</h2>
            <p class="text-gray-600 mb-4">Berbagi barang layak pakai untuk membantu sesama</p>
            <span class="font-medium text-blue-500">Mulai &rarr;</span>
        </a>
        <!-- Terima Barang -->
        <a href="{{ route('barang.index') }}" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
            <i data-lucide="package-check" class="w-16 h-16 text-green-500 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold mb-2">TERIMA BARANG</h2>
            <p class="text-gray-600 mb-4">Menerima bantuan barang sesuai kebutuhan secara gratis</p>
            <span class="font-medium text-green-500">Mulai &rarr;</span>
        </a>
        <!-- Chat Room -->
        <a href="#" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
            <i data-lucide="messages-square" class="w-16 h-16 text-purple-500 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold mb-2">CHAT ROOM</h2>
            <p class="text-gray-600 mb-4">Berkomunikasi langsung dengan pengguna lainnya</p>
            <span class="font-medium text-purple-500">Mulai &rarr;</span>
        </a>
    </div>

    <!-- Grid Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-md text-center">
            <i data-lucide="package" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
            <div class="text-3xl font-bold">{{ number_format($stats['barang_didonasikan']) }}</div>
            <p class="text-gray-600">Barang Didonasikan</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-md text-center">
            <i data-lucide="users" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
            <div class="text-3xl font-bold">{{ number_format($stats['pengguna_aktif']) }}</div>
            <p class="text-gray-600">Pengguna Aktif</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-md text-center">
            <i data-lucide="map-pin" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
            <div class="text-3xl font-bold">{{ $stats['kota'] }}</div>
            <p class="text-gray-600">Kota</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-md text-center">
            <i data-lucide="trending-up" class="w-8 h-8 text-gray-500 mx-auto mb-2"></i>
            <div class="text-3xl font-bold">{{ $stats['tingkat_keberhasilan'] }}%</div>
            <p class="text-gray-600">Tingkat Keberhasilan</p>
        </div>
    </div>

    <!-- Barang Terbaru -->
    <div>
        <h3 class="text-2xl font-bold mb-4">Barang Terbaru di Sekitar Anda</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($barangTerbaru as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <a href="{{ route('barang.show', $item->id) }}">
                        <!-- === PERBAIKAN PATH GAMBAR === -->
                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover hover:opacity-90 transition-opacity">
                        <div class="p-3">
                            <h4 class="font-semibold truncate text-sm md:text-base">{{ $item->nama_barang }}</h4>
                            <p class="text-sm text-gray-600 truncate">{{ $item->deskripsi }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $item->lokasi }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-600 col-span-full">Belum ada barang yang didonasikan.</p>
            @endforelse
        </div>
    </div>
@endsection