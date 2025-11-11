<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            /* Background vignette biru yang halus */
            background: radial-gradient(circle at center, #EBF8FF 0%, #D6E6F2 100%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="container mx-auto p-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-10">

            <!-- === PERUBAHAN KOLOM TEKS === -->
            <div class="bg-white/70 backdrop-blur-sm p-10 rounded-3xl shadow-lg text-center max-w-lg">
                <!-- Tipografi diubah menjadi lebih lembut dan rata kiri -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Selamat Datang di <span class="text-blue-600">ReuseForGood</span>
                </h1>
                <!-- Teks diubah menjadi normal case -->
                <p class="text-lg text-gray-600 mb-10">
                    Mari berbagi barang layak pakai untuk mereka yang membutuhkan.
                </p>
                <!-- Tombol diubah menjadi 'outline' (lebih elegan) -->
                <a href="{{ route('login') }}" 
                   class="inline-block bg-transparent border-2 border-blue-600 text-blue-600 font-bold py-3 px-10 rounded-lg text-lg transition duration-300 
                          hover:bg-blue-600 hover:text-white">
                    LOGIN
                </a>
            </div>

            <!-- Kolom Logo (Tidak Berubah) -->
            <div class="relative w-72 h-72 md:w-96 md:h-96 rounded-full shadow-2xl overflow-hidden flex items-center justify-center bg-white">
                <img src="{{ asset('foto/Logo.png') }}" 
                     alt="Logo RFG" 
                     class="absolute inset-0 w-full h-full object-contain p-0.5">
            </div>
            
        </div>
    </div>

</body>
</html>