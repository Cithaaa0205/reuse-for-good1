{{-- Ini meng-extend layout utama (yang belum dibuat) --}}
{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etalase Barang - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide-react@latest/dist/umd/lucide.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F0F4F8; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        .kategori-scroll::-webkit-scrollbar { display: none; }
        .kategori-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="p-4 md:p-8">

    <!-- Header (Simulasi) -->
    <header class="bg-white p-4 rounded-2xl shadow-sm mb-6 flex items-center justify-between max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="font-bold text-2xl text-blue-600">Reuse For Good</a>
        <div class="flex items-center gap-4">
            <a href="{{ route('barang.create') }}" class="font-semibold text-purple-600">Donasi</a>
            <a href="{{ route('barang.index') }}" class="font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">Penerima</a>
            <a href="{{ route('home') }}" class="font-semibold text-gray-700">Home</a>
            <!-- Ikon User -->
            <a href="#" class="text-gray-600"><i data-lucide="bell" class="w-6 h-6"></i></a>
            <a href="#" class="text-gray-600"><i data-lucide="user-circle" class="w-6 h-6"></i></a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto">
        <!-- Filter Kategori -->
        <div class="mb-6">
            <div class="flex items-center gap-3 kategori-scroll overflow-x-auto pb-3">
                <button class="flex-shrink-0 flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-full font-semibold">
                    <i data-lucide="list" class="w-5 h-5"></i> Semua
                </button>
                <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="shirt" class="w-5 h-5"></i> Pakaian
                </button>
                <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="football" class="w-5 h-5"></i> Olahraga
                </button>
                 <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="sofa" class="w-5 h-5"></i> Perabotan
                </button>
                <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="monitor" class="w-5 h-5"></i> Elektronik
                </button>
                <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="baby" class="w-5 h-5"></i> Bayi dan Anak
                </button>
                <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="watch" class="w-5 h-5"></i> Aksesoris
                </button>
                 <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="book" class="w-5 h-5"></i> Buku
                </button>
                 <button class="flex-shrink-0 flex items-center gap-2 bg-white text-gray-700 px-4 py-2 rounded-full font-medium shadow-sm">
                    <i data-lucide="pencil" class="w-5 h-5"></i> Alat Tulis
                </button>
            </div>
        </div>
        
        <!-- Search & Filter Jarak -->
        <div class="bg-white p-4 rounded-2xl shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow">
                    <input type="text" placeholder="Cari Barang yang anda butuhkan..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <button class="flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-4 py-3 rounded-lg font-medium">
                    <i data-lucide="sliders-horizontal" class="w-5 h-5"></i> Filter Jarak
                </button>
            </div>
            <div class="flex flex-wrap gap-3 mt-4">
                <button class="flex-1 md:flex-none text-sm bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-semibold">Semua Jarak</button>
                <button class="flex-1 md:flex-none text-sm bg-gray-100 text-gray-600 px-4 py-2 rounded-full font-medium">< 1km</button>
                <button class="flex-1 md:flex-none text-sm bg-gray-100 text-gray-600 px-4 py-2 rounded-full font-medium">< 5 km</button>
                <button class="flex-1 md:flex-none text-sm bg-gray-100 text-gray-600 px-4 py-2 rounded-full font-medium">< 10 km</button>
                <button class="flex-1 md:flex-none text-sm bg-gray-100 text-gray-600 px-4 py-2 rounded-full font-medium">< 25 km</button>
            </div>
        </div>

        <!-- Daftar Barang -->
        <section>
            <h3 class="flex items-center gap-2 text-2xl font-bold mb-4">
                <i data-lucide="sparkles" class="w-6 h-6 text-purple-600"></i>
                Rekomendasi untuk Anda
            </h3>
            <div class="card-grid mb-8">
                {{-- Loop barang rekomendasi di sini --}}
                @forelse($barang->take(5) as $item) {{-- Ambil 5 contoh --}}
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-105">
                        <a href="{{ route('barang.show', $item->id) }}" class="block">
                            <img src="{{ Storage::url('barang_donasi/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-36 object-cover">
                            <div class="p-3">
                                <h4 class="font-semibold truncate">{{ $item->nama_barang }}</h4>
                                <p class="text-sm text-gray-600 truncate h-10">{{ $item->deskripsi }}</p>
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $item->lokasi }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full">Belum ada barang.</p>
                @endforelse
            </div>
        </section>

        <section>
             <h3 class="flex items-center gap-2 text-2xl font-bold mb-4">
                <i data-lucide="map-pin" class="w-6 h-6 text-green-600"></i>
                Terdekat Dari Anda
            </h3>
            <div class="card-grid mb-8">
                {{-- Loop barang terdekat di sini --}}
                @forelse($barang->skip(5)->take(5) as $item) {{-- Ambil 5 contoh lagi --}}
                     <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-105">
                        <a href="{{ route('barang.show', $item->id) }}" class="block">
                            <img src="{{ Storage::url('barang_donasi/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-36 object-cover">
                            <div class="p-3">
                                <h4 class="font-semibold truncate">{{ $item->nama_barang }}</h4>
                                <p class="text-sm text-gray-600 truncate h-10">{{ $item->deskripsi }}</p>
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $item->lokasi }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full">Belum ada barang.</p>
                @endforelse
            </div>
        </section>

        <section>
             <h3 class="flex items-center gap-2 text-2xl font-bold mb-4">
                <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                Barang Terbaru
            </h3>
            <div class="card-grid mb-8">
                {{-- Loop semua barang dengan pagination --}}
                @forelse($barang as $item)
                     <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform hover:scale-105">
                        <a href="{{ route('barang.show', $item->id) }}" class="block">
                            <img src="{{ Storage::url('barang_donasi/' . $item->foto_barang_utama) }}" alt="{{ $item->nama_barang }}" class="w-full h-36 object-cover">
                            <div class="p-3">
                                <h4 class="font-semibold truncate">{{ $item->nama_barang }}</h4>
                                <p class="text-sm text-gray-600 truncate h-10">{{ $item->deskripsi }}</p>
                                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $item->lokasi }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full">Belum ada barang yang didonasikan.</p>
                @endforelse
            </div>
            
            <!-- Pagination Links -->
            <div class="mt-8">
                {{ $barang->links() }}
            </div>
        </section>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
{{-- @endsection --}}