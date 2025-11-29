<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Reuse For Good</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Lucide Icon --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Background lembut, sedikit glassmorphism feel */
            background:
                radial-gradient(circle at top, rgba(191, 219, 254, 0.9), transparent 55%),
                radial-gradient(circle at bottom, rgba(167, 243, 208, 0.7), transparent 55%),
                #eff6ff;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white/90 backdrop-blur-xl rounded-3xl shadow-[0_25px_60px_rgba(15,23,42,0.15)] border border-slate-100 overflow-hidden">

            {{-- LEFT: Brand / Highlight (tanpa statistik) --}}
            <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-blue-600 via-sky-500 to-cyan-400 text-white p-8 relative">
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,_#ffffff,_transparent_60%)]"></div>

                <div class="relative z-10 space-y-6">
                    <div class="inline-flex items-center gap-3 px-3 py-1 rounded-full bg-white/15 backdrop-blur text-xs font-medium">
                        <i data-lucide="leaf" class="w-4 h-4"></i>
                        Reuse For Good • Berbagi Kebaikan
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-3xl font-extrabold leading-snug">
                            Donasikan Barang,<br>
                            <span class="underline decoration-white/60">Ringankan</span> Sesama 🌱
                        </h1>
                        <p class="text-sm text-blue-50/90">
                            Ubah barang bekas layak pakai menjadi bantuan nyata. Reuse For Good menghubungkanmu
                            dengan orang-orang yang sedang membutuhkan, secara mudah dan gratis.
                        </p>
                    </div>

                    {{-- 3 poin keunggulan --}}
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3 bg-white/10 border border-white/15 rounded-2xl p-3">
                            <div class="mt-0.5">
                                <i data-lucide="gift" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Donasi dalam beberapa klik</p>
                                <p class="text-xs text-blue-100/90">
                                    Upload foto, isi detail barang, dan donasi langsung tayang di etalase.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-white/10 border border-white/15 rounded-2xl p-3">
                            <div class="mt-0.5">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Terhubung dengan sekitar</p>
                                <p class="text-xs text-blue-100/90">
                                    Temukan atau bagikan barang berdasarkan lokasi, jadi lebih dekat dan efisien.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-white/10 border border-white/15 rounded-2xl p-3">
                            <div class="mt-0.5">
                                <i data-lucide="messages-square" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white">Chat langsung dengan penerima</p>
                                <p class="text-xs text-blue-100/90">
                                    Koordinasi penjemputan atau pengiriman lewat chat di dalam platform.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 text-[11px] text-blue-100/80 mt-6">
                    “Kebaikan kecil dari satu orang bisa berarti dunia bagi orang lain.” 💙
                </div>
            </div>

            {{-- RIGHT: Form Login --}}
            <div class="p-6 sm:p-8 md:p-10 bg-white/95">
                {{-- Logo mobile --}}
                <div class="md:hidden flex flex-col items-center mb-6">
                    <div class="bg-white p-3 rounded-2xl shadow-md mb-3 border border-slate-100">
                        <img src="{{ asset('foto/Logo.png') }}" alt="Logo RFG" class="w-14 h-14">
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Reuse For Good</h2>
                    <p class="text-xs text-slate-500">Masuk untuk mulai berbagi kebaikan</p>
                </div>

                {{-- Title --}}
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Masuk</h2>
                    <p class="text-sm text-slate-500 mt-1">Masuk ke akun kamu dan lanjutkan kebaikanmu ✨</p>
                </div>

                {{-- Success message setelah registrasi --}}
                @if (session('success'))
                    <div class="mb-4 flex items-start gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm text-emerald-800">
                        <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Error --}}
                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                        <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
                        <div>
                            <p class="font-semibold mb-1 text-[13px]">Terjadi kesalahan saat masuk:</p>
                            <ul class="text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-medium text-slate-700">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                       bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                placeholder="contoh: nama@email.com"
                                required
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-medium text-slate-700">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full pl-9 pr-9 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                       bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                placeholder="Masukkan password kamu"
                                required
                            >
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                                tabindex="-1"
                            >
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Lupa password --}}
                    <div class="flex items-center justify-between text-xs mt-1 mb-1">
                        <span class="text-slate-400">
                            Jaga kerahasiaan akunmu 💙
                        </span>
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-700">
                            Lupa password?
                        </a>
                    </div>

                    {{-- Tombol --}}
                    <button
                        type="submit"
                        class="w-full mt-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold
                               bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-[0.99] transition"
                    >
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Masuk
                    </button>
                </form>

                <p class="text-center text-xs sm:text-sm text-slate-500 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                        Daftar sekarang
                    </a>
                </p>

                <p class="mt-4 text-[11px] text-center text-slate-400">
                    Dengan masuk, kamu menyetujui ketentuan penggunaan dan kebijakan privasi Reuse For Good.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }

            const togglePasswordBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                });
            }
        });
    </script>
</body>
</html>
