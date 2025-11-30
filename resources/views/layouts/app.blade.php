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
    
    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F1F5F9; /* slate-100-ish */
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
            display: block; padding: 0.5rem 0.75rem; border-radius: 0.75rem;
            color: #4B5563; background-color: #FFFFFF; border: 1px solid #E5E7EB;
            text-decoration: none; transition: all 0.2s;
            font-size: 0.875rem;
        }
        .pagination .page-link:hover { background-color: #F3F4F6; }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2563EB, #0EA5E9);
            color: #FFFFFF; border-color: #2563EB;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
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

    @stack('head')
</head>
<body class="min-h-screen flex flex-col relative">
@php
    $isAdminArea = request()->routeIs('admin.*');
@endphp

    <!-- Background dekoratif -->
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-10 w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute top-40 -right-20 w-72 h-72 bg-cyan-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -bottom-40 left-1/3 w-80 h-80 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>
    </div>

    {{-- ================================= HEADER ================================= --}}
    <header class="sticky top-0 z-40 backdrop-blur 
        {{ $isAdminArea ? 'bg-gradient-to-r from-slate-900 via-blue-900 to-sky-700 border-b border-slate-900/60 text-white' 
                        : 'bg-white/80 border-b border-slate-200/70' }}">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 gap-3">
                
                <!-- Kiri: Logo & Tombol Back -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    @if(View::hasSection('showBackButton') && View::getSection('showBackButton'))
                        <a href="@yield('backButtonUrl', 'javascript:history.back()')" 
                           class="p-1.5 rounded-full transition
                                  {{ $isAdminArea ? 'text-sky-100 hover:text-white hover:bg-white/10' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' }}">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        </a>
                    @endif

                    <a href="{{ $isAdminArea ? route('admin.dashboard') : route('home') }}" 
                       class="flex items-center gap-2 group">
                        <div class="h-10 w-10 rounded-2xl bg-white/95 shadow-md flex items-center justify-center overflow-hidden">
                            <img class="h-9 w-9 object-contain group-hover:scale-105 transition-transform" 
                                 src="{{ asset('foto/Logo.png') }}" alt="Logo">
                        </div>
                        <div class="hidden sm:flex flex-col leading-tight">
                            @if($isAdminArea)
                                <span class="font-extrabold text-lg tracking-tight text-white">
                                    Reuse <span class="text-sky-300">For Good</span>
                                </span>
                                <span class="text-[11px] text-sky-100/80">
                                    Panel Admin &amp; Manajemen Sistem
                                </span>
                            @else
                                <span class="font-extrabold text-lg text-slate-900 tracking-tight">
                                    Reuse <span class="text-blue-600">For Good</span>
                                </span>
                                <span class="text-[11px] text-slate-500">
                                    Donasi barang bekas, dampak nyata.
                                </span>
                            @endif
                        </div>
                    </a>
                </div>

                {{-- ======================== NAV ADMIN ======================== --}}
                @if($isAdminArea)
                    <div class="hidden md:flex md:items-center md:space-x-2 text-xs font-medium">
                        @if(Route::has('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-3 py-2 rounded-full transition border border-white/10
                                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white' : 'text-sky-100 hover:bg-white/10' }}">
                                Dashboard
                            </a>
                        @endif

                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 rounded-full transition border border-white/10
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/15 text-white' : 'text-sky-100 hover:bg-white/10' }}">
                            Pengguna
                        </a>

                        @if(Route::has('admin.barang.index'))
                            <a href="{{ route('admin.barang.index') }}"
                               class="px-3 py-2 rounded-full transition border border-white/10
                                      {{ request()->routeIs('admin.barang.*') ? 'bg-white/15 text-white' : 'text-sky-100 hover:bg-white/10' }}">
                                Barang
                            </a>
                        @endif

                        {{-- MENU BARU: LAPORAN --}}
                        @if(Route::has('admin.reports.index'))
                            <a href="{{ route('admin.reports.index') }}"
                               class="px-3 py-2 rounded-full transition border border-white/10
                                      {{ request()->routeIs('admin.reports.*') ? 'bg-white/15 text-white' : 'text-sky-100 hover:bg-white/10' }}">
                                Laporan
                            </a>
                        @endif

                        {{-- Kalau nanti punya halaman khusus pengajuan admin, ini bisa dipakai --}}
                        @if(Route::has('admin.requests.index'))
                            <a href="{{ route('admin.requests.index') }}"
                               class="px-3 py-2 rounded-full transition border border-white/10
                                      {{ request()->routeIs('admin.requests.*') ? 'bg-white/15 text-white' : 'text-sky-100 hover:bg-white/10' }}">
                                Pengajuan
                            </a>
                        @endif
                    </div>
                @else
                {{-- ======================== NAV USER (ASLI) ======================== --}}
                    <div class="hidden md:flex md:items-center md:space-x-1 text-sm font-medium">
                        <a href="{{ route('about') }}" 
                           class="px-3 py-2 rounded-full transition
                                  {{ request()->routeIs('about') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' }}">
                            Tentang Kami
                        </a>
                        <a href="{{ route('barang.create') }}"
                           class="px-3 py-2 rounded-full transition
                                  {{ request()->routeIs('barang.create') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' }}">
                            Donasi
                        </a>
                        <a href="{{ route('barang.index') }}"
                           class="px-3 py-2 rounded-full transition
                                  {{ request()->routeIs('barang.index') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-blue-600 hover:bg-slate-100' }}">
                            Terima
                        </a>
                    </div>
                @endif

                <!-- Kanan: Ikon & Profil -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @guest
                        @if(!$isAdminArea)
                            <a href="{{ route('login') }}" 
                               class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-500 px-3 py-1.5 rounded-full hover:bg-blue-50">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-4 py-2 text-xs sm:text-sm font-semibold rounded-full text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 shadow-md transition">
                                Daftar
                            </a>
                        @endif
                    @endguest
                    
                    @auth
                        @if($isAdminArea)
                            <span class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold bg-white/10 text-sky-100 border border-white/20">
                                <i data-lucide="shield" class="w-3 h-3 mr-1.5"></i>
                                Mode Admin
                            </span>
                        @else
                            <!-- Tombol Chat (hanya di sisi user) -->
                            <a href="{{ route('chat.index') }}" 
                               class="hidden sm:inline-flex text-slate-500 hover:text-blue-600 p-2 rounded-full hover:bg-slate-100 transition relative">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                            </a>
                        @endif

                        <!-- Dropdown Profil -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <!-- Tombol Pemicu (Avatar) -->
                            <button @click="open = !open" 
                                    class="flex items-center focus:outline-none border-2 border-transparent hover:border-blue-100 rounded-full transition p-0.5 bg-white shadow-sm">
                                @if(Auth::user()->foto_profil)
                                    <img class="h-9 w-9 rounded-full object-cover" src="{{ asset('uploads/avatars/' . Auth::user()->foto_profil) }}" alt="Foto">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center font-bold text-xs">
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
                                 class="origin-top-right absolute right-0 mt-2 w-60 rounded-2xl shadow-xl bg-white ring-1 ring-black/5 focus:outline-none z-50 overflow-hidden">
                                
                                <!-- Header Dropdown -->
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-[11px] text-slate-500 uppercase font-semibold tracking-wide">Halo,</p>
                                    <p class="text-sm font-semibold text-slate-900 truncate">
                                        {{ Auth::user()->nama_lengkap }}
                                    </p>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1 text-sm">
                                    <a href="{{ route('profile.show', Auth::user()->username) }}" 
                                       class="flex items-center px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <i data-lucide="user" class="w-4 h-4 mr-3 text-slate-400"></i> Profil Saya
                                    </a>
                                    @if(!$isAdminArea)
                                        <a href="{{ route('request.manage') }}" 
                                           class="flex items-center px-4 py-2 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            <i data-lucide="inbox" class="w-4 h-4 mr-3 text-slate-400"></i> Kelola Pengajuan
                                        </a>
                                    @endif
                                </div>

                                <!-- Footer Dropdown (Logout) -->
                                <div class="border-t border-slate-100 bg-slate-50 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-semibold">
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
    <main class="flex-grow w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer sederhana -->
    <footer class="border-t border-slate-200 bg-white/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] sm:text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Reuse For Good. Gerakan donasi barang bekas layak pakai.</p>
            <p>Made with ❤️ untuk lingkungan & sesama.</p>
        </div>
    </footer>

    <!-- Script Inisialisasi Ikon & Search Tabel -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }

            // Pencarian sederhana tabel admin (kalau ada input dengan id "user-search")
            const searchInput = document.getElementById('user-search');
            const table = document.getElementById('user-table');

            if (searchInput && table) {
                const rows = table.querySelectorAll('tbody tr[data-row]');
                searchInput.addEventListener('input', () => {
                    const q = searchInput.value.toLowerCase();
                    rows.forEach(row => {
                        const text = row.getAttribute('data-row') || '';
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
