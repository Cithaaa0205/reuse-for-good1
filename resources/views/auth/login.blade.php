<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(120deg, #E0F7FA 0%, #B2EBF2 100%);
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen px-4">

    <!-- Logo dan Judul -->
    <div class="text-center mb-6">
        <div class="bg-white p-4 inline-block rounded-full shadow-lg mb-4">
             <img src="{{ asset('foto/Logo.png') }}" alt="Logo RFG" class="w-20 h-20">
        </div>
        <h1 class="text-3xl font-bold text-gray-800">Reuse For Good</h1>
        <p class="text-gray-600">Berbagi untuk Kebaikan</p>
    </div>

    <!-- Kotak Form Login -->
    <div class="bg-white w-full max-w-md p-8 md:p-10 rounded-2xl shadow-xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Masuk</h2>
        <p class="text-center text-gray-500 mb-8">Masuk ke akun Anda</p>

        <!-- === TAMBAHAN DI SINI === -->
        <!-- Pesan Sukses setelah Registrasi -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <!-- === AKHIR TAMBAHAN === -->


        <!-- Tampilkan error jika login gagal -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan email Anda" required>
            </div>

            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</Mabel>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Masukkan password Anda" required>
            </div>
            
            <!-- Lupa password (opsional) -->
            <div class="text-right mb-6">
                <a href="#" class="text-sm font-medium text-blue-500 hover:text-blue-600">
                    Lupa password?
                </a>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 text-lg">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-600 mt-8">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="font-medium text-blue-500 hover:text-blue-600">
                Daftar di sini
            </a>
        </p>
    </div>

</body>
</html>