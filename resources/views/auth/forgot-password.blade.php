<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top, rgba(191, 219, 254, 0.9), transparent 55%), radial-gradient(circle at bottom, rgba(167, 243, 208, 0.7), transparent 55%), #eff6ff; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-[0_25px_60px_rgba(15,23,42,0.15)] border border-slate-100 p-8">
            
            {{-- LOGIKA TAMPILAN: --}}
            {{-- Jika berhasil generate password, tampilkan Hasilnya --}}
            @if (session('generated_password'))
                
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Reset Berhasil!</h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Password akun kamu telah diubah menjadi:
                    </p>

                    {{-- KOTAK PASSWORD --}}
                    <div class="mt-6 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl p-5 relative group">
                        <code id="newPassword" class="text-3xl font-mono font-bold text-slate-800 tracking-wider select-all">
                            {{ session('generated_password') }}
                        </code>
                    </div>

                    {{-- [FITUR BARU] REMINDER ALERT DI SINI --}}
                    <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-100 text-left flex gap-3 items-start animate-pulse">
                        <div class="mt-0.5 text-amber-600">
                            <i data-lucide="triangle-alert" class="w-5 h-5"></i>
                        </div>
                        <div class="text-xs text-amber-900 leading-relaxed">
                            <span class="font-bold block text-amber-700 mb-0.5">PENTING:</span>
                            Password ini bersifat sementara. Demi keamanan, mohon <b>segera ganti password</b> ini di menu <u>Edit Profil</u> setelah kamu berhasil masuk.
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="mt-6 space-y-3">
                        <button onclick="copyPassword()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold bg-slate-800 text-white shadow-md hover:bg-slate-900 transition active:scale-95">
                            <i data-lucide="copy" class="w-4 h-4"></i> Salin Password
                        </button>
                        
                        <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl text-sm font-semibold bg-blue-600 text-white shadow-md hover:bg-blue-700 transition">
                            Masuk Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            {{-- Jika belum (Tampilan Form Biasa) --}}
            @else
                
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 mb-4">
                        <i data-lucide="key-round" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Lupa Password?</h2>
                    <p class="text-sm text-slate-500 mt-2">Masukkan email kamu, sistem akan membuatkan password sementara secara instan.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                        <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
                        <ul class="text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-medium text-slate-700">Email Terdaftar</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-[0.99] transition">
                        <i data-lucide="zap" class="w-4 h-4"></i> Reset Password Instan
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 inline-flex items-center gap-1 transition">
                        <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali ke Login
                    </a>
                </div>

            @endif
        </div>
    </div>
    <script> 
        lucide.createIcons(); 

        function copyPassword() {
            // Ambil teks password
            const passwordText = document.getElementById('newPassword').innerText.trim();
            
            // Salin ke clipboard
            navigator.clipboard.writeText(passwordText).then(() => {
                alert('Password berhasil disalin! Silakan paste saat login.');
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
</body>
</html>