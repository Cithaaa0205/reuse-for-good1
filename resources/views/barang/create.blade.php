<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Barang - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- INI ADALAH PERBAIKANNYA: Menggunakan 'lucide' BUKAN 'lucide-react' -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white p-4 shadow-sm">
        <div class="max-w-3xl mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
                <!-- Ikon ini SEKARANG akan muncul -->
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                <span class="font-semibold">Donasi Barang</span>
            </a>
            <a href="{{ route('home') }}" class="font-bold text-xl text-blue-600">Reuse For Good</a>
        </div>
    </header>

    <!-- Form Utama -->
    <main class="max-w-3xl mx-auto p-6">
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <p class="font-bold">Oops! Ada kesalahan:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Card 1: Foto Barang -->
            <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
                <h2 class="text-xl font-bold mb-4">Foto Barang</h2>
                
                <!-- Kontainer Preview -->
                <div id="preview-container" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 transition">
                    <!-- Ikon ini SEKARANG akan muncul -->
                    <i data-lucide="upload-cloud" class="w-12 h-12 text-gray-400 mx-auto"></i>
                    <p class="font-semibold text-gray-700 mt-2">Tambah Foto</p>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 5 foto. Foto Pertama akan menjadi foto utama.</p>
                </div>
                
                <!-- Input file yang tersembunyi -->
                <input type="file" id="foto_barang" name="foto_barang" accept="image/*" class="hidden" required> 
                
                <p class="text-xs text-gray-500 mt-2">Maksimal 5 foto. Foto Pertama akan menjadi foto utama.</p>
            </div>

            <!-- Card 2: Informasi Barang -->
            <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
                <h2 class="text-xl font-bold mb-4">Informasi Barang</h2>
                <div class="space-y-4">
                    <div>
                        <label for="nama_barang" class="block mb-2 text-sm font-medium text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Sepatu Nike Air Max uk. 42" required>
                    </div>
                    
                    <div>
                        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Deskripsikan kondisi barang, riwayat penggunaan, dan hal penting lainnya..." required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kategori_id" class="block mb-2 text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select id="kategori_id" name="kategori_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="kondisi" class="block mb-2 text-sm font-medium text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                            <select id="kondisi" name="kondisi" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kondisi</mption>
                                <option value="Baru" {{ old('kondisi') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Layak Pakai" {{ old('kondisi') == 'Layak Pakai' ? 'selected' : '' }}>Layak Pakai</option>
                                <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Lokasi & Pengambilan -->
            <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
                <h2 class="text-xl font-bold mb-4">Lokasi & Pengambilan</h2>
                 <div class="space-y-4">
                    <div>
                        <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                        <select id="lokasi" name="lokasi" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Lokasi</option>
                            <option value="Yogyakarta" {{ old('lokasi') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="Sleman" {{ old('lokasi') == 'Sleman' ? 'selected' : '' }}>Sleman</option>
                            <option value="Bantul" {{ old('lokasi') == 'Bantul' ? 'selected' : '' }}>Bantul</option>
                            <option value="Kulon Progo" {{ old('lokasi') == 'Kulon Progo' ? 'selected' : '' }}>Kulon Progo</option>
                            <option value="Gunungkidul" {{ old('lokasi') == 'Gunungkidul' ? 'selected' : '' }}>Gunungkidul</option>
                        </select>
                    </div>
                    <div>
                        <label for="catatan_pengambilan" class="block mb-2 text-sm font-medium text-gray-700">Catatan Pengambilan</label>
                        <textarea id="catatan_pengambilan" name="catatan_pengambilan" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Contoh: Bisa diambil di weekdays 17.00 - 20.00, Detail Lokasi Bisa Chat">{{ old('catatan_pengambilan') }}</textarea>
                    </div>
                 </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="w-1/3 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition duration-300">
                    Batal
                </a>
                <button type="submit" class="w-2/3 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    Posting Donasi
                </button>
            </div>
        </form>
    </main>

    <script>
        // Script ini (yang dibungkus DOMContentLoaded) sudah benar.
        // Masalahnya ada di <head>
        document.addEventListener('DOMContentLoaded', () => {
            
            // Sekarang ini akan berhasil
            lucide.createIcons();

            const inputFoto = document.getElementById('foto_barang');
            const previewContainer = document.getElementById('preview-container');
            const placeholderContent = previewContainer.innerHTML; 

            previewContainer.addEventListener('click', () => {
                inputFoto.click();
            });

            inputFoto.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewContainer.innerHTML = ''; 
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = "Preview Foto Barang";
                        img.className = "w-auto h-auto max-w-full max-h-64 mx-auto object-contain rounded-lg"; 
                        previewContainer.appendChild(img);
                        previewContainer.classList.remove('p-6');
                        previewContainer.classList.add('p-2');
                    }
                    
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.innerHTML = placeholderContent;
                    previewContainer.classList.add('p-6');
                    previewContainer.classList.remove('p-2'); 
                    lucide.createIcons(); 
                }
            });
        });
    </script>
</body>
</html>