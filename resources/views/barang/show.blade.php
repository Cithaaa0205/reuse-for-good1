@extends('layouts.app')

@section('title', $barang->nama_barang)

{{-- Tampilkan tombol back --}}
@section('showBackButton', true)

@section('content')
<main class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Kolom Kiri: Gambar -->
        <div>
            @if($barang->foto_barang_utama)
            <img src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}" alt="{{ $barang->nama_barang }}" class="w-full h-auto object-cover rounded-2xl shadow-lg aspect-square">
            @else
            <div class="w-full h-auto bg-gray-200 rounded-2xl shadow-lg aspect-square flex items-center justify-center">
                <i data-lucide="image-off" class="w-20 h-20 text-gray-400"></i>
            </div>
            @endif
            <!-- Galeri foto tambahan bisa ditambahkan di sini -->
        </div>

        <!-- Kolom Kanan: Info & Aksi -->
        <div class="flex flex-col gap-6">
            <!-- Info Utama -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-start mb-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $barang->nama_barang }}</h1>
                    
                    <!-- Tombol Favorit -->
                    @auth
                        @php $isFavorited = in_array($barang->id, $favoriteIds ?? []); @endphp
                        
                        <form action="{{ route('favorite.toggle', $barang->id) }}" method="POST" class="z-10">
                            @csrf
                            <button type="submit" class="favorite-btn p-1.5 rounded-full text-gray-400 hover:text-red-500 transition {{ $isFavorited ? 'favorited' : '' }}">
                                <i data-lucide="heart" class="w-6 h-6 icon-outline"></i>
                                <i data-lucide="heart" class="w-6 h-6 icon-filled fill-current {{ $isFavorited ? 'text-red-500' : '' }}"></i>
                            </button>
                        </form>
                    @endauth
                </div>
                
                @if($barang->kategori)
                <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mb-4">
                    {{ $barang->kategori->nama_kategori }}
                </span>
                @endif
                <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                <p class="text-gray-700 mb-4">{{ $barang->deskripsi }}</p>
                <p class="text-sm text-gray-500">Kondisi: <span class="font-medium text-gray-800">{{ $barang->kondisi }}</span></p>
                <p class="text-sm text-gray-500 mt-1">Lokasi: <span class="font-medium text-gray-800">{{ $barang->lokasi }}</span></p>
            </div>

            <!-- Info Donatur -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <a href="{{ route('profile.show', $barang->donatur->username) }}" class="flex items-center gap-4 group">
                    @if($barang->donatur->foto_profil)
                        <img class="h-14 w-14 rounded-full object-cover" src="{{ asset('uploads/avatars/' . $barang->donatur->foto_profil) }}" alt="Avatar">
                    @else
                        <div class="h-14 w-14 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($barang->donatur->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h4 class="text-lg font-semibold group-hover:text-blue-600 transition-colors">{{ $barang->donatur->nama_lengkap }}</h4>
                        <p class="text-sm text-gray-600">4.8 <i data-lucide="star" class="w-4 h-4 inline-block text-yellow-400 fill-current -mt-1"></i> | {{ $barang->donatur->barangDonasis->count() }} donasi</p>
                    </div>
                </a>
            </div>

            <!-- Tombol Aksi -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                @if(Auth::check())
                    {{-- 1. Jika user adalah PEMILIK barang --}}
                    @if(Auth::id() == $barang->donatur_id)
                        <p class="text-center text-gray-600 mb-3">Ini adalah donasi Anda.</p>
                        <!-- Form Hapus Donasi -->
                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                                Hapus Donasi Ini
                            </button>
                        </form>
                    
                    {{-- 2. Jika user adalah PENGUNJUNG --}}
                    @else
                        <form action="{{ route('request.store', $barang->id) }}" method="POST">
                            @csrf
                            @if($sudahDiajukan)
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
                        
                        <!-- Tombol Hubungi Pendonasi (DIUPDATE) -->
                        <a href="{{ route('chat.show', $barang->donatur->id) }}" class="block text-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300 mt-3">
                            Hubungi Pendonasi
                        </a>
                    @endif
                
                {{-- 3. Jika user BELUM LOGIN --}}
                @else
                    <a href="{{ route('login') }}" class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                        Login untuk Mengajukan
                    </a>
                @endif
            </div>

            <!-- Aksi Lain -->
            <div class="flex justify-around bg-white p-4 rounded-2xl shadow-md">
                <button class="flex items-center gap-2 text-gray-600 hover:text-blue-600">
                    <i data-lucide="share-2" class="w-5 h-5"></i> Bagikan
                </button>
                    <button class="flex items-center gap-2 text-gray-600 hover:text-red-600">
                    <i data-lucide="flag" class="w-5 h-5"></i> Laporkan
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
                        @if($item->foto_barang_utama)
                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover">
                        @else
                        <div class="w-full h-32 md:h-40 bg-gray-200 flex items-center justify-center">
                            <i data-lucide="image-off" class="w-10 h-10 text-gray-400"></i>
                        </div>
                        @endif
                        <div class="p-3">
                            <h4 class="font-semibold truncate text-sm md:text-base">{{ $item->nama_barang }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->lokasi }}</p>
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