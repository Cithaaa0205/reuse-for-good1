    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Reuse For Good</title>
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
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900">Buat Password Baru</h2>
                <p class="text-sm text-slate-500 mt-2">Silakan masukkan password baru untuk akunmu.</p>
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

            <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email (Readonly) --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly class="w-full px-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-500 bg-slate-100 cursor-not-allowed">
                </div>

                {{-- Password Baru --}}
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-medium text-slate-700">Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                        </span>
                        <input type="password" id="password" name="password" required class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="Minimal 8 karakter">
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-700">Ulangi Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="Ketik ulang password">
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-[0.99] transition">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Password
                </button>
            </form>
        </div>
    </div>
    <script> lucide.createIcons(); </script>
</body>
</html>
```

---

### 5. Terakhir, Update `login.blade.php`

Ganti tombol "Lupa password?" di file login kamu menjadi link ke route yang baru kita buat.

```html
{{-- Cari bagian ini di login.blade.php --}}
<a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-700">
    Lupa password?
</a>
```

### Hal Penting (Jangan Lupa):
Agar email benar-benar terkirim, kamu harus mengonfigurasi file **`.env`** di folder project kamu.
Jika kamu masih testing di lokal (localhost), gunakan **Mailtrap** atau log driver.

**Contoh setting `.env` pakai Log (email akan muncul di file `storage/logs/laravel.log`, tidak dikirim beneran):**
```env
MAIL_MAILER=log