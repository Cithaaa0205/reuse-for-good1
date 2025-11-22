@extends('layouts.app')

@section('title', 'Kelola Pengajuan')

{{-- Tombol back --}}
@section('showBackButton', true)

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center justify-center md:justify-start gap-2">
            <i data-lucide="inbox" class="w-6 h-6"></i> Kelola Pengajuan
        </h1>
        <p class="text-gray-600 mt-1">Pilih penerima yang tepat untuk barang Anda</p>
    </div>

    <!-- Tab Navigasi & Konten -->
    <div x-data="{ activeTab: 'menunggu' }">
        
        <!-- Statistik/Tab Navigasi -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <!-- Tab Menunggu -->
            <button @click="activeTab = 'menunggu'" 
                    :class="{ 'border-blue-600 ring-2 ring-blue-100': activeTab === 'menunggu', 'border-gray-200': activeTab !== 'menunggu' }"
                    class="bg-white p-4 rounded-xl border shadow-sm hover:shadow-md transition text-center cursor-pointer relative overflow-hidden group">
                <div class="text-3xl font-bold text-red-500">{{ $menunggu->count() }}</div>
                <div class="text-sm text-gray-600 font-medium">Menunggu</div>
                <div :class="{ 'bg-blue-600': activeTab === 'menunggu' }" class="absolute bottom-0 left-0 w-full h-1 bg-transparent transition-colors"></div>
            </button>

            <!-- Tab Diterima -->
            <button @click="activeTab = 'diterima'" 
                    :class="{ 'border-green-600 ring-2 ring-green-100': activeTab === 'diterima', 'border-gray-200': activeTab !== 'diterima' }"
                    class="bg-white p-4 rounded-xl border shadow-sm hover:shadow-md transition text-center cursor-pointer relative overflow-hidden">
                <div class="text-3xl font-bold text-gray-800">{{ $diterima->count() }}</div>
                <div class="text-sm text-gray-600 font-medium">Diterima</div>
                <div :class="{ 'bg-green-600': activeTab === 'diterima' }" class="absolute bottom-0 left-0 w-full h-1 bg-transparent transition-colors"></div>
            </button>

            <!-- Tab Ditolak -->
            <button @click="activeTab = 'ditolak'" 
                    :class="{ 'border-gray-400 ring-2 ring-gray-100': activeTab === 'ditolak', 'border-gray-200': activeTab !== 'ditolak' }"
                    class="bg-white p-4 rounded-xl border shadow-sm hover:shadow-md transition text-center cursor-pointer relative overflow-hidden">
                <div class="text-3xl font-bold text-gray-800">{{ $ditolak->count() }}</div>
                <div class="text-sm text-gray-600 font-medium">Ditolak</div>
                <div :class="{ 'bg-gray-400': activeTab === 'ditolak' }" class="absolute bottom-0 left-0 w-full h-1 bg-transparent transition-colors"></div>
            </button>
        </div>

        <!-- Konten Tab: Menunggu -->
        <div x-show="activeTab === 'menunggu'" class="space-y-4">
            @forelse($menunggu as $req)
                @include('profile.partials.request-card', ['req' => $req, 'status' => 'menunggu'])
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 border-dashed">
                    <p class="text-gray-500">Belum ada pengajuan yang menunggu.</p>
                </div>
            @endforelse
        </div>

        <!-- Konten Tab: Diterima -->
        <div x-show="activeTab === 'diterima'" class="space-y-4" style="display: none;">
            @forelse($diterima as $req)
                @include('profile.partials.request-card', ['req' => $req, 'status' => 'diterima'])
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 border-dashed">
                    <p class="text-gray-500">Belum ada pengajuan yang diterima.</p>
                </div>
            @endforelse
        </div>

        <!-- Konten Tab: Ditolak -->
        <div x-show="activeTab === 'ditolak'" class="space-y-4" style="display: none;">
            @forelse($ditolak as $req)
                @include('profile.partials.request-card', ['req' => $req, 'status' => 'ditolak'])
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-200 border-dashed">
                    <p class="text-gray-500">Belum ada pengajuan yang ditolak.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- Script AlpineJS --}}
@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush
@endsection