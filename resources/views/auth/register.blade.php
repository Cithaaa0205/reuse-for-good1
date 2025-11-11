<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            /* Background vignette biru yang halus */
            background: radial-gradient(circle at center, #EBF8FF 0%, #D6E6F2 100%);
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen py-10 px-4">

    <!-- Judul -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Reuse For Good</h1>
        <p class="text-gray-600">Berbagi untuk Kebaikan</p>
    </div>

    <!-- Kotak Form -->
    <div class="bg-white w-full max-w-lg p-8 md:p-10 rounded-2xl shadow-xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Registrasi</h2>
        <p class="text-center text-gray-500 mb-8">Buat akun baru Anda</p>

        <!-- Tampilkan error jika ada -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nama lengkap" required>
                </div>
                
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan email" required>
                </div>

                <div>
                    <label for="nomor_telepon" class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan nomor telepon" required>
                </div>

                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan username" required>
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Masukkan password" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Konfirmasi password" required>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 text-lg mt-8">
                Daftar
            </button>
        </form>

        <p class="text-center text-gray-600 mt-8">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-medium text-blue-500 hover:text-blue-600">
                Masuk di sini
            </a>
        </p>
    </div>

</body>
</html>