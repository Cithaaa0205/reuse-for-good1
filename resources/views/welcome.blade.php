<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#D6E6F2] flex items-center justify-center min-h-screen">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-center p-8 gap-10">
        
        <!-- Kolom Teks -->
        <div class="bg-white/70 backdrop-blur-sm p-10 rounded-3xl shadow-lg text-center md:text-left max-w-lg">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                SELAMAT DATANG DI REUSEFORGOOD
            </h1>
            <p class="text-lg text-gray-700 mb-8">
                MARI BERBAGI BARANG LAYAK PAKAI UNTUK MEREKA YANG MEMBUTUHKAN
            </p>
            <a href="{{ route('login') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-10 rounded-lg text-lg transition duration-300">
                LOGIN
            </a>
        </div>

        <!-- Kolom Logo -->
        <div class="bg-white p-8 rounded-full shadow-2xl w-64 h-64 md:w-96 md:h-96 flex items-center justify-center">
            <div class="text-center">
                <!-- Ganti dengan tag <img> jika Anda punya file logo -->
                <div class="text-blue-500 text-4xl font-bold mb-2">R.F.G</div>
                <img src="https://placehold.co/150x100/EBF8FF/3B82F6?text=LOGO" alt="Logo RFG" class="mx-auto mb-4 w-32">
                <div class="text-2xl font-semibold text-gray-800">Reuse For Good</div>
            </div>
        </div>

    </div>
</body>
</html>