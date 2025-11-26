@extends('layouts.app')

@section('title', 'Etalase Barang Donasi')

@section('content')

<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

<h1 class="text-2xl font-bold mb-6 text-gray-800">Etalase Barang</h1>

{{-- 🔍 FORM SEARCH --}}
<form action="{{ route('barang.index') }}" method="GET" class="mb-8 flex shadow-md rounded-lg overflow-hidden border border-gray-200">
    <input type="text"
           name="search"
           value="{{ request('search') }}"
           placeholder="Cari barang, kategori, lokasi..."
           class="w-full px-4 py-3 text-gray-700 focus:outline-none focus:ring-0 border-none"
    >
    
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-3 transition duration-150 ease-in-out flex items-center justify-center w-16">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>
</form>

{{-- 🟦 KATEGORI BAR --}}
<h2 class="text-xl font-semibold mb-4 text-gray-700">Kategori</h2>
<div class="flex flex-wrap gap-2 mb-8">
    <a href="{{ url('/barang') }}"
        class="px-4 py-2 rounded-full {{ request('kategori') ? 'bg-gray-200' : 'bg-blue-600 text-white' }}">
        Semua
    </a>

    @foreach ($kategoris as $kategori)
        <a href="{{ url('/barang?kategori=' . $kategori->slug) }}"
            class="px-4 py-2 rounded-full
            {{ request('kategori') === $kategori->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            {{ $kategori->nama_kategori }}
        </a>
    @endforeach
</div>

{{-- 🟨 FILTER LOKASI --}}
<h2 class="text-xl font-semibold mb-3 text-gray-700">Filter Lokasi</h2>
<div class="flex flex-wrap gap-2 mb-8">
    <a href="{{ url('/barang?jarak=1') }}" class="px-4 py-2 rounded-full bg-gray-200 hover:bg-gray-300"> &lt; 1 km </a>
    <a href="{{ url('/barang?jarak=5') }}" class="px-4 py-2 rounded-full bg-gray-200 hover:bg-gray-300"> &lt; 5 km </a>
    <a href="{{ url('/barang?jarak=10') }}" class="px-4 py-2 rounded-full bg-gray-200 hover:bg-gray-300"> &lt; 10 km </a>
</div>

{{-- 🟪 JUDUL REKOMENDASI --}}
<h2 class="text-xl font-semibold mb-6 text-gray-700">
    Rekomendasi untuk Anda
</h2>

{{-- 🟫 GRID BARANG --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($barang as $item)
        <a href="{{ route('barang.show', $item->id) }}" class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden transform hover:scale-[1.02]">
            @if($item->foto_barang_utama)
                <img src="{{ asset('uploads/barang/'.$item->foto_barang_utama) }}"
                     onerror="this.onerror=null; this.src='https://placehold.co/400x300/f3f4f6/a1a1aa?text=No+Image';"
                     alt="{{ $item->nama_barang }}"
                     class="w-full h-48 object-cover object-center"
                >
            @endif
            <div class="p-4">
                <p class="text-sm font-medium text-blue-600">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                <p class="font-bold text-gray-900 mt-1">{{ $item->nama_barang }}</p>
                <p class="text-xs text-gray-500 mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    {{ $item->kabupaten }}, {{ $item->provinsi }}
                </p>

                {{-- 🟢 TAMPILKAN JARAK --}}
                @if(isset($item->distance))
                    <p class="text-xs text-green-600 font-semibold mt-1">
                        {{ number_format($item->distance, 1) }} km dari lokasi Anda
                    </p>
                @endif

            </div>
        </a>
    @empty
        <div class="col-span-full bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <p class="font-medium text-yellow-800">Tidak ada barang ditemukan pada kategori atau pencarian ini.</p>
        </div>
    @endforelse
</div>

{{-- PAGINATION --}}
<div class="mt-8">
    {{ $barang->withQueryString()->links() }}
</div>

</div>

{{-- 📍 SCRIPT GEOLOCATION --}}
<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        fetch("{{ route('location.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                latitude: position.coords.latitude,
                longitude: position.coords.longitude
            })
        });
    }, function(error) {
        console.log("Lokasi tidak diizinkan");
    });
}
</script>

@endsection
