<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reuse For Good')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F0F4F8; /* Latar belakang abu-abu muda */
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c5c5c5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

        /* Kustomisasi pagination Tailwind */
        .pagination { display: flex; justify-content: center; align-items: center; padding: 1rem 0; }
        .pagination span, .pagination a {
            padding: 0.5rem 0.75rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-decoration: none;
        }
        .pagination .page-item.active .page-link {
            background-color: #2563EB; /* bg-blue-600 */
            color: white;
            font-weight: 600;
        }
        .pagination .page-item:not(.active) .page-link {
            background-color: white;
            color: #4B5563; /* text-gray-600 */
        }
        .pagination .page-item:not(.active) .page-link:hover {
            background-color: #F3F4F6; /* bg-gray-100 */
        }
        .pagination .page-item.disabled .page-link {
            color: #D1D5DB; /* text-gray-300 */
            cursor: not-allowed;
        }

        /* === TAMBAHAN CSS FAVORIT === */
        .favorite-btn .icon-outline { display: block; }
        .favorite-btn .icon-filled { display: none; }
        .favorite-btn.favorited .icon-outline { display: none; }
        .favorite-btn.favorited .icon-filled { display: block; }
        /* === AKHIR TAMBAHAN === */
    </style>
</head>
<body class="min-h-screen">

    <!-- Header Utama -->
    <header class="bg-white p-4 shadow-md sticky top-0 z-50">
        <nav class="container mx-auto max-w-7xl flex items-center justify-between">
            
            <!-- Logo dan Tombol Back -->
            <div class="flex items-center gap-4">
                @hasSection('showBackButton')
                    <a href="javascript:history.back()" class="text-gray-600 hover:text-blue-600 p-2 rounded-full hover:bg-gray-100">
                        <i data-lucide="arrow-left" class="w-6 h-6"></i>
                    </a>
                @else
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-blue-600">Reuse For Good</span>
                    </a>
                @endif
            </div>

            <!-- Search (Hanya tampil di halaman tertentu jika diperlukan) -->
            @if(Route::is('barang.index'))
            <div class="hidden md:block w-full max-w-md">
                <div class="relative">
                    <input type="text" placeholder="Cari Barang..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>
            @endif

            <!-- Navigasi Kanan -->
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}" class="hidden md:block text-gray-600 font-medium hover:text-blue-600">Tentang Kami</a>
                <a href="{{ route('barang.create') }}" class="hidden md:block text-gray-600 font-medium hover:text-blue-600">Donasi</a>
                <a href="{{ route('barang.index') }}" class="hidden md:block text-gray-600 font-medium hover:text-blue-600">Terima</a>
                
                <a href="#" class="text-gray-600 hover:text-blue-600 p-2 rounded-full hover:bg-gray-100">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                </a>

                <!-- Dropdown Profil -->
                @auth
                <div class="relative group pt-2">
                    <button class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-300 hover:border-blue-500">
                        <img src="{{ Auth::user()->foto_profil ? asset('uploads/avatars/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap) . '&background=E0F7FA&color=0284C7' }}" 
                             alt="Avatar" class="w-full h-full object-cover">
                    </button>
                    <!-- Menu Dropdown (Perbaikan CSS Celah) -->
                    <div class="absolute right-0 w-48 bg-white rounded-lg shadow-xl overflow-hidden hidden group-hover:block">
                        <a href="{{ route('profile.show', Auth::user()->username) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Kelola Pengajuan</a>
                        <div class="border-t border-gray-100"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-3 text-sm text-red-600 hover:bg-gray-100">Keluar</button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Konten Halaman -->
    <main class="container mx-auto max-w-7xl p-4 md:p-6">
        @yield('content')
    </main>

    <script>
        // Panggil createIcons SETELAH DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    
    {{-- Stack untuk script tambahan per halaman --}}
    @stack('scripts')
</body>
</html>