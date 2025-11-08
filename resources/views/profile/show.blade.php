@extends('layouts.app')

@section('title', 'Profil ' . $user->nama_lengkap)

@section('showBackButton', true)

@section('content')
<div class="max-w-4xl mx-auto">
    
        <!-- Kartu Profil Utama -->
    <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 mb-6">
        <!-- === PERBARUI GAMBAR === -->
        <img src="{{ $user->foto_profil ? asset('uploads/avatars/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_lengkap) . '&background=E0F7FA&color=0284C7&size=128' }}"
             alt="Avatar" class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-blue-100 shadow-md object-cover">
        
        <div class="flex-1 text-center md:text-left">
            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->nama_lengkap }}</h1>
                @if(Auth::id() == $user->id)
                <a href="{{ route('profile.edit') }}" class="mt-2 md:mt-0 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Edit Profil
                </a>
                @endif
            </div>
            <p class="text-gray-600 mb-3">&#64;{{ $user->username }} &middot; Bergabung {{ $user->created_at->translatedFormat('F Y') }}</p>
            
            <!-- Stats -->
            <!-- ... (Stats tidak berubah) ... -->

            <!-- === TAMBAHKAN DESKRIPSI === -->
            <p class="text-gray-700 mt-4 text-center md:text-left">
                {{ $user->deskripsi ?? 'Suka berbagi barang yang masih layak pakai untuk yang membutuhkan. Mari kita ciptakan lingkungan yang lebih sustainable!' }}
            </p>
        </div>
    </div>

    <!-- Tab Navigasi -->
    <div class="mb-6">
        <div class="border-b border-gray-300">
            <nav class="flex -mb-px" aria-label="Tabs">
                <a href="#" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm text-blue-600 border-blue-600" aria-current="page">
                    Barang Didonasikan
                </a>
                <a href="#" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Barang Diterima
                </a>
                <a href="#" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300">
                    Favorit
                </a>
            </nav>
        </div>
    </div>

    <!-- Grid Konten Tab -->
    <div>
        <!-- Konten Tab 1: Barang Didonasikan (Aktif) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @forelse($barangDonasi as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <a href="{{ route('barang.show', $item->id) }}">
                        <!-- === PERBAIKAN PATH GAMBAR === -->
                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-32 md:h-40 object-cover">
                        <div class="p-3">
                            <h4 class="font-semibold truncate text-sm md:text-base">{{ $item->nama_barang }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $item->lokasi }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <p class="text-gray-600 col-span-full text-center py-10">
                    <i data-lucide="package-x" class="w-12 h-12 mx-auto text-gray-400 mb-2"></i>
                    {{ $user->nama_lengkap }} belum mendonasikan barang.
                </p>
            @endforelse
        </div>
    </div>

</div>
@endsection