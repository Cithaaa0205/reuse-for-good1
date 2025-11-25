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
        <a href="{{ route('barang.create') }}" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
            <i data-lucide="gift" class="w-16 h-16 text-blue-500 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold mb-2">DONASIKAN BARANG</h2>
            <p class="text-gray-600 mb-4">Berbagi barang layak pakai untuk membantu sesama</p>
            <span class="font-medium text-blue-500">Mulai &rarr;</span>
        </a>
        <a href="{{ route('barang.index') }}" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
            <i data-lucide="package-check" class="w-16 h-16 text-green-500 mx-auto mb-4"></i>
            <h2 class="text-xl font-bold mb-2">TERIMA BARANG</h2>
            <p class="text-gray-600 mb-4">Menerima bantuan barang sesuai kebutuhan secara gratis</p>
            <span class="font-medium text-green-500">Mulai &rarr;</span>
        </a>
        <a href="{{ route('chat.index') }}" class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow text-center">
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
        <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mx-auto mb-2"></i>
        <div class="text-3xl font-bold">{{ number_format($stats['barang_diterima']) }}</div>
        <p class="text-gray-600">Barang Diterima</p>
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
</div>


    <!-- Barang Terbaru -->
    <div>
        <h3 class="text-2xl font-bold mb-4">Barang Terbaru di Sekitar Anda</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($barangTerbaru as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden relative group">
                    
                    <!-- 1. Link Gambar (Sekarang di Atas dalam Kode, di Bawah secara Visual) -->
                    <a href="{{ route('barang.show', $item->id) }}" class="block relative z-0">
                        @if($item->foto_barang_utama)
                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover hover:opacity-90 transition-opacity">
                        @else
                        <div class="w-full h-32 md:h-40 bg-gray-200 flex items-center justify-center">
                            <i data-lucide="image-off" class="w-10 h-10 text-gray-400"></i>
                        </div>
                        @endif
                        <div class="p-3">
                            <h4 class="font-semibold truncate text-sm md:text-base text-gray-800">{{ $item->nama_barang }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->lokasi }}</p>
                        </div>
                    </a>

                    <!-- 2. Tombol Favorit (Dipindah ke BAWAH agar menimpa Link) -->
                    @auth
                        @php $isFavorited = in_array($item->id, $favoriteIds); @endphp
                        
                        <form action="{{ route('favorite.toggle', $item->id) }}" method="POST" class="absolute top-2 right-2 z-20">
                            @csrf
                            <button type="submit" class="favorite-btn p-2 rounded-full bg-white/80 hover:bg-white text-gray-500 hover:text-red-500 shadow-sm transition {{ $isFavorited ? 'favorited text-red-500' : '' }}">
                                <i data-lucide="heart" class="w-5 h-5 icon-outline"></i>
                                <i data-lucide="heart" class="w-5 h-5 icon-filled fill-current"></i>
                            </button>
                        </form>
                    @endauth
                    
                </div>
            @empty
                <p class="text-gray-600 col-span-full text-center py-10">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                    Belum ada barang yang didonasikan.
                </p>
            @endforelse
        </div>
    </div>
@endsection