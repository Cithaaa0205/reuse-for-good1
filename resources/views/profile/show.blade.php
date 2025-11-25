@extends('layouts.app')

@section('title', 'Profil ' . $user->nama_lengkap)

{{-- Tampilkan tombol back jika BUKAN profil kita sendiri --}}
@if(Auth::check() && Auth::id() !== $user->id)
    @section('showBackButton', true)
@endif

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Card Info Profil -->
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <!-- Foto Profil -->
            @if($user->foto_profil)
                <img class="h-24 w-24 md:h-32 md:w-32 rounded-full object-cover shadow-md"
                     src="{{ asset('uploads/avatars/' . $user->foto_profil) }}"
                     alt="Foto Profil">
            @else
                <div class="h-24 w-24 md:h-32 md:w-32 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-5xl shadow-md">
                    {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->nama_lengkap), 0, 2)) }}
                </div>
            @endif
            
            <!-- Info Teks -->
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->nama_lengkap }}</h1>
                    @if(Auth::check() && Auth::id() == $user->id)
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 mt-3 sm:mt-0">
                            <i data-lucide="edit-3" class="w-4 h-4 mr-2"></i>
                            Edit Profil
                        </a>
                    @endif
                </div>
                <p class="text-gray-500 mb-1">
                    @<span>{{ $user->username }}</span> · Bergabung {{ $user->created_at->isoFormat('MMMM YYYY') }}
                </p>
                <div class="flex justify-center sm:justify-start gap-6 text-gray-600 my-3">
                    <span>
                        <strong class="text-gray-800">{{ $barangDonasi->count() }}</strong> Donasi
                    </span>
                    <span>
                        <strong class="text-gray-800">4.8</strong>
                        <i data-lucide="star" class="w-4 h-4 inline-block text-yellow-400 fill-current -mt-1"></i>
                        Rating
                    </span>
                </div>
                <p class="text-gray-700 max-w-lg">
                    {{ $user->deskripsi ?? 'Pengguna ini belum menambahkan deskripsi.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- SATU x-data untuk NAV + SEMUA KONTEN TAB --}}
    <div x-data="{ activeTab: 'donasi' }" x-cloak>
        <!-- Tab Navigasi -->
        <div class="mb-6">
            <div class="border-b border-gray-300">
                <nav class="flex -mb-px space-x-6">
                    <a href="#donasi"
                       @click.prevent="activeTab = 'donasi'"
                       :class="{
                            'border-blue-600 text-blue-600': activeTab === 'donasi',
                            'border-transparent text-gray-700 hover:border-gray-400': activeTab !== 'donasi'
                        }"
                       class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        Barang Didonasikan
                    </a>

                    @if(Auth::check() && Auth::id() == $user->id)
                        <a href="#diterima"
                           @click.prevent="activeTab = 'diterima'"
                           :class="{
                                'border-blue-600 text-blue-600': activeTab === 'diterima',
                                'border-transparent text-gray-700 hover:border-gray-400': activeTab !== 'diterima'
                            }"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Barang Diterima
                        </a>

                        <a href="#favorit"
                           @click.prevent="activeTab = 'favorit'"
                           :class="{
                                'border-blue-600 text-blue-600': activeTab === 'favorit',
                                'border-transparent text-gray-700 hover:border-gray-400': activeTab !== 'favorit'
                            }"
                           class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Favorit
                        </a>
                    @endif
                </nav>
            </div>
        </div>

        <!-- KONTEN TAB -->
        <!-- Tab 1: Barang Didonasikan (Publik) -->
        <div x-show="activeTab === 'donasi'">
            @if($barangDonasi->isEmpty())
                <div class="text-center text-gray-500 p-10 bg-white rounded-2xl shadow">
                    <i data-lucide="package-x" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                    <p>{{ $user->nama_lengkap }} belum mendonasikan barang.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($barangDonasi as $item)
                        <div class="bg-white rounded-xl shadow overflow-hidden">
                            <a href="{{ route('barang.show', $item->id) }}">
                                @if($item->foto_barang_utama)
                                    <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                         alt="{{ $item->nama_barang }}"
                                         class="w-full h-32 md:h-40 object-cover">
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
                    @endforeach
                </div>
            @endif
        </div>

        @if(Auth::check() && Auth::id() == $user->id)
            <!-- Tab 2: Barang Diterima (Privat) -->
            <div x-show="activeTab === 'diterima'" style="display: none;">
                @if($barangDiterima->isEmpty())
                    <div class="text-center text-gray-500 p-10 bg-white rounded-2xl shadow">
                        <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                        <p>Anda belum menerima barang donasi.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($barangDiterima as $item)
                            <div class="bg-white rounded-xl shadow overflow-hidden">
                                <a href="{{ route('barang.show', $item->id) }}">
                                    @if($item->foto_barang_utama)
                                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                             alt="{{ $item->nama_barang }}"
                                             class="w-full h-32 md:h-40 object-cover">
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
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tab 3: Favorit (Privat) -->
            <div x-show="activeTab === 'favorit'" style="display: none;">
                @if($favorites->isEmpty())
                    <div class="text-center text-gray-500 p-10 bg-white rounded-2xl shadow">
                        <i data-lucide="heart-off" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                        <p>Anda belum mem-favoritkan barang apapun.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($favorites as $item)
                            <div class="bg-white rounded-xl shadow overflow-hidden relative">
                                <a href="{{ route('barang.show', $item->id) }}">
                                    @if($item->foto_barang_utama)
                                        <img src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                             alt="{{ $item->nama_barang }}"
                                             class="w-full h-32 md:h-40 object-cover">
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

                                {{-- Tombol un-favorite cepat (opsional) --}}
                                <form action="{{ route('favorite.toggle', $item->id) }}"
                                      method="POST"
                                      class="absolute top-2 right-2">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 rounded-full bg-white/80 hover:bg-white text-red-500 shadow-sm transition">
                                        <i data-lucide="heart" class="w-4 h-4 fill-current"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush
@endsection
