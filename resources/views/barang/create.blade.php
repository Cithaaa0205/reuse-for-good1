<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Barang - Reuse For Good</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

<header class="bg-white p-4 shadow-sm">
    <div class="max-w-3xl mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span class="font-semibold">Donasi Barang</span>
        </a>
        <a href="{{ route('home') }}" class="font-bold text-xl text-blue-600">Reuse For Good</a>
    </div>
</header>

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

        <!-- FOTO BARANG -->
        <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Foto Barang</h2>

            <div id="preview-container"
                 class="border-2 border-dashed border-gray-300 rounded-lg p-6 grid grid-cols-2 md:grid-cols-3 gap-4 text-center cursor-pointer hover:bg-gray-50 transition relative max-h-80 overflow-y-auto">

                <div id="placeholder" class="flex flex-col items-center justify-center col-span-full py-6">
                    <i data-lucide="upload-cloud" class="w-12 h-12 text-gray-400"></i>
                    <p class="font-semibold text-gray-700 mt-2">Tambah Foto</p>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 5 foto. Foto pertama menjadi foto utama.</p>
                </div>
            </div>

            <input type="file" id="foto_barang" name="foto_barang[]" accept="image/*" class="hidden" multiple>
        </div>

        <!-- INFORMASI -->
        <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Informasi Barang</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Nama Barang *</label>
                    <input type="text" name="nama_barang" class="w-full px-4 py-3 border rounded-lg"
                           placeholder="Contoh: Sepatu Nike Air Max uk. 42" required>
                </div>

                <div>
                    <label class="text-sm font-medium">Deskripsi *</label>
                    <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 border rounded-lg"
                              placeholder="Deskripsikan kondisi barang..." required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Kategori *</label>
                        <select name="kategori_id" class="w-full px-4 py-3 border rounded-lg" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Kondisi *</label>
                        <select name="kondisi" class="w-full px-4 py-3 border rounded-lg" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baru">Baru</option>
                            <option value="Layak Pakai">Layak Pakai</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOKASI -->
        <div class="bg-white p-6 rounded-2xl shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Lokasi & Pengambilan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Provinsi *</label>
                    <select id="provinsi" name="provinsi" class="w-full px-4 py-3 border rounded-lg" required>
                        <option value="">Pilih Provinsi</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Kabupaten/Kota *</label>
                    <select id="kabupaten" name="kabupaten" class="w-full px-4 py-3 border rounded-lg" required>
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- TOMBOL -->
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="w-1/3 text-center bg-gray-200 py-3 rounded-lg font-bold">Batal</a>
            <button type="submit" class="w-2/3 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold">
                Posting Donasi
            </button>
        </div>
    </form>
</main>

<!-- SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    lucide.createIcons();

    // ===============================
    // PROVINSI - KABUPATEN
    // ===============================
    const kabupatenData = {
        "DI Yogyakarta": ["Yogyakarta", "Sleman", "Bantul", "Kulon Progo", "Gunungkidul"],
        "Jawa Tengah": ["Semarang", "Surakarta", "Magelang", "Tegal", "Purwokerto"],
        "Jawa Barat": ["Bandung", "Bogor", "Bekasi", "Tasikmalaya", "Cirebon"],
        "Jawa Timur": ["Surabaya", "Malang", "Kediri", "Madiun", "Banyuwangi"],
    };

    const provinsiSelect = document.getElementById("provinsi");
    const kabupatenSelect = document.getElementById("kabupaten");

    provinsiSelect.addEventListener("change", function () {
        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        if (kabupatenData[this.value]) {
            kabupatenData[this.value].forEach(k => {
                kabupatenSelect.insertAdjacentHTML("beforeend", `<option value="${k}">${k}</option>`);
            });
        }
    });

    // ===============================
    // MULTIPLE UPLOAD FOTO
    // ===============================
    const inputFoto = document.getElementById("foto_barang");
    const previewContainer = document.getElementById("preview-container");
    const placeholder = document.getElementById("placeholder");

    let selectedFiles = [];

    previewContainer.addEventListener("click", (e) => {
        if (e.target.tagName.toLowerCase() !== "img") {
            inputFoto.click();
        }
    });

    inputFoto.addEventListener("change", function () {
        const newFiles = Array.from(this.files);

        if (selectedFiles.length + newFiles.length > 5) {
            alert("Maksimal upload 5 foto!");
            return;
        }

        placeholder.classList.add("hidden");

        newFiles.forEach(file => {
            selectedFiles.push(file);

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.className = "w-full h-40 object-contain rounded-lg border cursor-pointer";
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });

        this.value = "";
    });

    // ===============================
    // MASUKKAN FILE KE INPUT SAAT SUBMIT
    // ===============================
    document.querySelector("form").addEventListener("submit", function (e) {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        inputFoto.files = dt.files;
    });

});
</script>
</body>
</html>
