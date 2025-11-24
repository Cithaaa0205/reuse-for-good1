@extends('layouts.app')

@section('title', 'Etalase Barang')

@section('content')
    <!-- Filter Kategori -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3">Kategori</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('barang.index') }}" 
               class="px-5 py-2 rounded-lg text-sm font-medium transition
                      {{ !request()->has('kategori') ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                Semua
            </a>
            @foreach($kategoris as $kategori)
            <a href="{{ route('barang.index', ['kategori' => $kategori->slug]) }}"
               class="px-5 py-2 rounded-lg text-sm font-medium transition
                      {{ request('kategori') == $kategori->slug ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                {{ $kategori->nama_kategori }}
            </a>
            @endforeach
        </div>
    </div>
    
    <!-- Filter Jarak (Placeholder) -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-800 mb-3">Filter Jarak</h3>
        <div class="flex flex-wrap gap-3">
            <button class="px-5 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-100 transition">Semua Jarak</button>
            <button class="px-5 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-100 transition">&lt; 1 km</button>
            <button class="px-5 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-100 transition">&lt; 5 km</button>
            <button class="px-5 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-100 transition">&lt; 10 km</button>
        </div>
    </div>

    <!-- Grid Barang -->
    <div>
        <h3 class="text-2xl font-bold mb-4">Rekomendasi untuk Anda</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($barang as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden relative group">
                    
                    <!-- 1. Link Gambar -->
                    <a href="{{ route('barang.show', $item->id) }}" class="block relative z-0">
                        @if($item->foto_barang_utama)
                        <img src="{{ asset('uploads/barang/'. $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover hover:opacity-90 transition-opacity">
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

                    <!-- 2. Tombol Favorit (Dipindah ke Bawah & Z-Index Tinggi agar bisa diklik) -->
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
                 <div class="text-center py-10 col-span-full">
                    <div class="bg-white p-8 rounded-2xl shadow-sm inline-block">
                        <i data-lucide="search-x" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                        <p class="text-gray-600 font-medium">Belum ada barang di etalase</p>
                        @if(request()->has('kategori'))
                            <p class="text-sm text-gray-500 mt-1">untuk kategori ini.</p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $barang->withQueryString()->links() }}
        </div>
    </div>
@endsection