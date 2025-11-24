<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reuse For Good')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Alpine.js (Wajib untuk fitur Klik/Dropdown) -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F0F4F8; 
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

        /* Pagination Styling */
        .pagination { display: flex; justify-content: center; padding: 1rem 0; }
        .pagination .page-item { margin: 0 0.25rem; }
        .pagination .page-link {
            display: block; padding: 0.5rem 0.75rem; border-radius: 0.5rem;
            color: #4B5563; background-color: #FFFFFF; border: 1px solid #D1D5DB;
            text-decoration: none; transition: all 0.2s;
        }
        .pagination .page-link:hover { background-color: #F3F4F6; }
        .pagination .page-item.active .page-link {
            background-color: #2563EB; color: #FFFFFF; border-color: #2563EB;
        }
        .pagination .page-item.disabled .page-link { color: #D1D5DB; cursor: not-allowed; }

        /* CSS Favorit */
        .favorite-btn .icon-outline { display: block; }
        .favorite-btn .icon-filled { display: none; }
        .favorite-btn.favorited .icon-outline { display: none; }
        .favorite-btn.favorited .icon-filled { display: block; }
        
        /* Mencegah flickering pada elemen Alpine.js */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header Utama -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Kiri: Logo & Tombol Back -->
                <div class="flex-shrink-0 flex items-center gap-4">
                    @if(View::hasSection('showBackButton') && View::getSection('showBackButton'))
                        <a href="@yield('backButtonUrl', 'javascript:history.back()')" class="text-gray-600 hover:text-blue-600 p-2 rounded-full hover:bg-gray-100 transition">
                            <i data-lucide="arrow-left" class="w-6 h-6"></i>
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <!-- Pastikan file logo ada di public/foto/Logo.png -->
                        <img class="h-10 w-10 group-hover:scale-105 transition-transform" src="{{ asset('foto/Logo.png') }}" alt="Logo">
                        <span class="font-bold text-xl text-blue-600 hidden sm:inline">Reuse For Good</span>
                    </a>
                </div>

                <!-- Tengah: Navigasi Utama (Desktop) -->
                <div class="hidden md:flex md:items-center md:space-x-1">
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md text-sm font-medium transition">Tentang Kami</a>
                    <a href="{{ route('barang.create') }}" class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md text-sm font-medium transition">Donasi</a>
                    <a href="{{ route('barang.index') }}" class="text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md text-sm font-medium transition">Terima</a>
                </div>

                <!-- Kanan: Ikon & Profil -->
                <div class="flex items-center gap-2 sm:gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 px-3 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                            Daftar
                        </a>
                    @endguest
                    
                    @auth
                        <!-- Tombol Chat -->
                        <a href="{{ route('chat.index') }}" class="text-gray-500 hover:text-blue-600 p-2 rounded-full hover:bg-gray-100 transition relative">
                            <i data-lucide="message-circle" class="w-6 h-6"></i>
                        </a>

                        <!-- Dropdown Profil (Menggunakan Alpine.js MURNI, Hapus Hover CSS) -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            
                            <!-- Tombol Pemicu (Avatar) -->
                            <button @click="open = !open" class="flex items-center focus:outline-none border-2 border-transparent hover:border-blue-100 rounded-full transition p-0.5">
                                @if(Auth::user()->foto_profil)
                                    <img class="h-9 w-9 rounded-full object-cover shadow-sm" src="{{ asset('uploads/avatars/' . Auth::user()->foto_profil) }}" alt="Foto">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ strtoupper(substr(Auth::user()->nama_lengkap, 0, 2)) }}
                                    </div>
                                @endif
                            </button>

                            <!-- Menu Dropdown -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 x-cloak
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden">
                                
                                <!-- Header Dropdown -->
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <p class="text-xs text-gray-500 uppercase font-bold">Halo,</p>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nama_lengkap }}</p>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i data-lucide="user" class="w-4 h-4 mr-3 text-gray-400"></i> Profil Saya
                                    </a>
                                    <a href="{{ route('request.manage') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i data-lucide="inbox" class="w-4 h-4 mr-3 text-gray-400"></i> Kelola Pengajuan
                                    </a>
                                </div>

                                <!-- Footer Dropdown (Logout) -->
                                <div class="border-t border-gray-100 bg-gray-50 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                            <i data-lucide="log-out" class="w-4 h-4 mr-3"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Konten Halaman -->
    <main class="flex-grow container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Script Inisialisasi Ikon -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    
    @stack('scripts')
</body>
</html>