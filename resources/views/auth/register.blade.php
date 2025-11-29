<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Reuse For Good</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Lucide Icon --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top, rgba(219, 234, 254, 0.9), transparent 55%),
                radial-gradient(circle at bottom, rgba(204, 251, 241, 0.7), transparent 55%),
                #eff6ff;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 bg-white/90 backdrop-blur-xl rounded-3xl shadow-[0_25px_60px_rgba(15,23,42,0.15)] border border-slate-100 overflow-hidden">

            {{-- LEFT: Brand / Copy --}}
            <div class="hidden md:flex flex-col justify-between bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 text-white p-8 relative">
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top,_#ffffff,_transparent_60%)]"></div>

                <div class="relative z-10 space-y-6">
                    <div class="inline-flex items-center gap-3 px-3 py-1 rounded-full bg-white/15 backdrop-blur text-xs font-medium">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        Buat akun baru • Gratis selamanya
                    </div>

                    <div class="space-y-3">
                        <h1 class="text-3xl font-extrabold leading-snug">
                            Bergabung dengan<br>
                            Komunitas <span class="underline decoration-white/60">Reuse For Good</span>
                        </h1>
                        <p class="text-sm text-emerald-50/90">
                            Dengan mendaftar, kamu bisa mendonasikan barang, mengajukan penerimaan,
                            dan terhubung langsung dengan donatur maupun penerima lain.
                        </p>
                    </div>

                    <ul class="space-y-2 text-sm text-emerald-50/90">
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                            <span>Donasi barang bekas layak pakai dengan mudah.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                            <span>Ajukan permintaan barang sesuai kebutuhanmu.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                            <span>Semua gratis, tanpa biaya administrasi.</span>
                        </li>
                    </ul>
                </div>

                <div class="relative z-10 text-[11px] text-emerald-50/90 mt-6">
                    Mari bareng-bareng kurangi sampah dan bantu mereka yang membutuhkan 🌍
                </div>
            </div>

            {{-- RIGHT: Form Register --}}
            <div class="p-6 sm:p-8 md:p-10 bg-white/95">
                {{-- Logo mobile --}}
                <div class="md:hidden flex flex-col items-center mb-6">
                    <div class="bg-white p-3 rounded-2xl shadow-md mb-3 border border-slate-100">
                        <img src="{{ asset('foto/Logo.png') }}" alt="Logo RFG" class="w-14 h-14">
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Reuse For Good</h2>
                    <p class="text-xs text-slate-500">Buat akun baru untuk mulai berbagi</p>
                </div>

                {{-- Title --}}
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">Registrasi</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Lengkapi data di bawah ini untuk membuat akun baru.
                    </p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                        <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
                        <div>
                            <p class="font-semibold mb-1 text-[13px]">Terjadi kesalahan pada data yang dikirim:</p>
                            <ul class="text-xs space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>- {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama lengkap --}}
                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="nama_lengkap" class="block text-xs font-medium text-slate-700">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                </span>
                                <input
                                    type="text"
                                    id="nama_lengkap"
                                    name="nama_lengkap"
                                    value="{{ old('nama_lengkap') }}"
                                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                           bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-medium text-slate-700">Email</label>
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

                        {{-- Nomor telepon --}}
                        <div class="space-y-1.5">
                            <label for="nomor_telepon" class="block text-xs font-medium text-slate-700">Nomor Telepon</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                </span>
                                <input
                                    type="tel"
                                    id="nomor_telepon"
                                    name="nomor_telepon"
                                    value="{{ old('nomor_telepon') }}"
                                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                           bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                    placeholder="Masukkan nomor telepon"
                                    required
                                >
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="username" class="block text-xs font-medium text-slate-700">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i data-lucide="at-sign" class="w-4 h-4 text-slate-400"></i>
                                </span>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username') }}"
                                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                           bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                    placeholder="Pilih username unik"
                                    required
                                >
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-medium text-slate-700">Password</label>
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
                                    placeholder="Minimal 8 karakter"
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

                        {{-- Konfirmasi Password --}}
                        <div class="space-y-1.5">
                            <label for="password_confirmation" class="block text-xs font-medium text-slate-700">Konfirmasi Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i data-lucide="check" class="w-4 h-4 text-slate-400"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                           bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                                    placeholder="Ulangi password"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full mt-4 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold
                               bg-emerald-600 text-white shadow-md hover:bg-emerald-700 hover:shadow-lg active:scale-[0.99] transition"
                    >
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Daftar Sekarang
                    </button>
                </form>

                <p class="text-center text-xs sm:text-sm text-slate-500 mt-6">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                        Masuk di sini
                    </a>
                </p>

                <p class="mt-4 text-[11px] text-center text-slate-400">
                    Data yang kamu isi akan kami jaga dan hanya digunakan untuk keperluan platform Reuse For Good.
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
