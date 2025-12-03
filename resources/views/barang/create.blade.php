@extends('layouts.app')

@section('title', 'Donasi Barang')
@section('showBackButton', true)
@section('backButtonUrl', route('barang.index'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ANIMASI KHUSUS HALAMAN DONASI --}}
    <style>
        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .hero-animate {
            opacity: 0;
            animation: heroFadeUp .65s ease-out forwards;
        }

        .hero-animate-delay-1 { animation-delay: .08s; }
        .hero-animate-delay-2 { animation-delay: .16s; }

        @keyframes heroOrbFloat {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
            }
            50% {
                transform: translate3d(18px, -12px, 0) scale(1.06);
            }
        }

        .hero-orb-1 {
            animation: heroOrbFloat 22s ease-in-out infinite;
        }

        .hero-orb-2 {
            animation: heroOrbFloat 28s ease-in-out infinite alternate;
        }

        @keyframes cardFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-animate {
            opacity: 0;
            animation: cardFadeUp .55s ease-out forwards;
        }

        @keyframes dropzonePulse {
            0%, 100% {
                transform: translateY(0);
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.02);
            }
            50% {
                transform: translateY(-3px);
                box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            }
        }

        .dropzone-animate:hover {
            animation: dropzonePulse .9s ease-in-out;
        }
    </style>

    {{-- Header page (sekilas hero kecil) --}}
    <section class="hero-animate relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-md mb-2">
        {{-- efek radial halus --}}
        <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_left,_#ffffff,_transparent_55%)]"></div>

        {{-- orb dekoratif --}}
        <div class="pointer-events-none hero-orb-1 absolute -bottom-8 -right-10 w-40 h-40 rounded-full border border-white/30 opacity-40"></div>
        <div class="pointer-events-none hero-orb-2 absolute -top-10 right-1/3 w-28 h-28 rounded-full border border-white/25 opacity-35"></div>

        <div class="relative px-5 sm:px-7 py-5 sm:py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-[11px] tracking-[0.16em] font-semibold uppercase text-blue-100">
                    Form Donasi
                </p>
                <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                    Buat Donasi Baru
                </h1>
                <p class="text-xs sm:text-sm text-blue-50/90 mt-1.5 max-w-xl">
                    Isi detail barang yang ingin kamu donasikan. Barang layak pakai akan sangat bermanfaat bagi orang lain ✨
                </p>
            </div>

            {{-- mini info step di kanan --}}
            <div class="hidden sm:flex flex-col items-end gap-2 hero-animate hero-animate-delay-1">
                <div class="px-3 py-2 rounded-2xl bg-white/10 backdrop-blur shadow-sm text-right">
                    <p class="text-[11px] text-blue-50/90">Langkah pengisian</p>
                    <p class="text-xs font-semibold text-white">
                        1. Foto • 2. Info • 3. Lokasi
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="card-animate flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
             style="animation-delay:.05s">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
            <div>
                <p class="font-semibold text-[13px] mb-1">Oops! Ada beberapa hal yang perlu dicek:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- container untuk menampung input file (wajib untuk proses submit) -->
        <div id="photo-inputs-container"></div>

        {{-- FOTO BARANG --}}
        <section
            class="card-animate bg-gradient-to-br from-blue-50 via-sky-50 to-emerald-50 rounded-3xl p-[1px] shadow-lg shadow-sky-100/60 border border-slate-100"
            style="animation-delay:.08s"
        >
            <div class="bg-white/90 backdrop-blur rounded-[1.45rem] p-5 sm:p-6 space-y-4">

                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Foto Barang</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            Tambahkan hingga <span class="font-semibold">5 foto</span>. Foto pertama akan menjadi foto utama di etalase.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-600/5 text-[11px] font-medium text-blue-700 border border-blue-100">
                        <i data-lucide="image" class="w-3 h-3"></i>
                        Format JPG/PNG, maks 5MB
                    </span>
                </div>

                {{-- Wrapper + dropzone --}}
                <div class="rounded-3xl bg-gradient-to-br from-blue-100/40 via-sky-100/40 to-emerald-100/40 p-[1.5px] shadow-inner shadow-sky-100">
                    <div
                        id="preview-container"
                        class="dropzone-animate group border border-dashed border-slate-300/70 rounded-[1.35rem] px-4 sm:px-6 py-6 grid grid-cols-2 md:grid-cols-3 gap-4
                               text-center cursor-pointer bg-white/70 hover:bg-white transition max-h-80 overflow-y-auto"
                    >
                        <div id="placeholder" class="flex flex-col items-center justify-center col-span-full py-6">
                            <div class="w-14 h-14 rounded-[1.2rem] bg-slate-50 shadow-sm border border-slate-200 flex items-center justify-center mb-3">
                                <i data-lucide="upload-cloud" class="w-7 h-7 text-slate-400 group-hover:-translate-y-0.5 group-hover:scale-105 transition"></i>
                            </div>
                            <p class="font-semibold text-slate-900 text-sm sm:text-base">
                                Klik untuk menambahkan foto
                            </p>
                            <p class="text-xs sm:text-[13px] text-slate-500 mt-1 max-w-md">
                                Seret untuk memilih beberapa sekaligus dari perangkatmu. Pilih foto yang jelas agar penerima bisa melihat kondisi barang dengan baik.
                            </p>
                        </div>
                    </div>
                </div>

                <input type="file" id="foto_barang" name="foto_barang[]" accept="image/*" class="hidden" multiple>
            </div>
        </section>

        {{-- INFORMASI BARANG --}}
        <section class="card-animate bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4"
                 style="animation-delay:.15s">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Informasi Barang</h2>
                <span class="text-[11px] text-slate-400">Semua kolom bertanda * wajib diisi</span>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-700">Nama Barang <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="nama_barang"
                        value="{{ old('nama_barang') }}"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        placeholder="Contoh: Sepatu Nike Air Max uk. 42"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-700">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea
                        name="deskripsi"
                        rows="4"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        placeholder="Deskripsikan kondisi barang (misal: masih 90% mulus, pernah dipakai 3 kali, minus sedikit lecet di bagian sol)..."
                        required
                    >{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-700">Kategori <span class="text-red-500">*</span></label>
                        <select
                            name="kategori_id"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                   bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        >
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-slate-700">Kondisi <span class="text-red-500">*</span></label>
                        <select
                            name="kondisi"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                   bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        >
                            <option value="">Pilih Kondisi</option>
                            <option value="Baru" {{ old('kondisi') == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Layak Pakai" {{ old('kondisi') == 'Layak Pakai' ? 'selected' : '' }}>Layak Pakai</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        {{-- LOKASI --}}
        <section class="card-animate bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4"
                 style="animation-delay:.22s">
            <div class="flex items-center justify-between gap-2">
                <h2 class="text-lg sm:text-xl font-semibold text-slate-900">Lokasi & Pengambilan</h2>
                <span class="inline-flex items-center gap-1 text-[11px] text-slate-500">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    Pastikan lokasi sesuai untuk memudahkan penjemputan
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-700">Provinsi <span class="text-red-500">*</span></label>
                    <select
                        id="provinsi"
                        name="provinsi"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required
                    >
                        <option value="">Pilih Provinsi</option>
                        <option value="DI Yogyakarta" {{ old('provinsi') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                        <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                        <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                        <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-700">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select
                        id="kabupaten"
                        name="kabupaten"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required
                    >
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>
            </div>
        </section>

        {{-- BUTTON --}}
        <div class="card-animate flex flex-col sm:flex-row items-center gap-3 pt-1" style="animation-delay:.28s">
            <a href="{{ route('home') }}"
               class="w-full sm:w-1/3 inline-flex items-center justify-center px-4 py-2.5 rounded-2xl text-sm font-semibold
                      bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition">
                Batal
            </a>

            <button
                type="submit"
                class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold
                       bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-[0.99] transition">
                <i data-lucide="send" class="w-4 h-4"></i>
                Posting Donasi
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    // ====================== PROVINSI → KABUPATEN ======================
    const kabupatenData = {
        "DI Yogyakarta": ["Yogyakarta", "Sleman", "Bantul", "Kulon Progo", "Gunungkidul"],
        "Jawa Tengah":    ["Semarang", "Surakarta", "Magelang", "Tegal", "Purwokerto"],
        "Jawa Barat":     ["Bandung", "Bogor", "Bekasi", "Tasikmalaya", "Cirebon"],
        "Jawa Timur":     ["Surabaya", "Malang", "Kediri", "Madiun", "Banyuwangi"],
    };

    const provinsiSelect = document.getElementById("provinsi");
    const kabupatenSelect = document.getElementById("kabupaten");

    if (provinsiSelect && kabupatenSelect) {
        provinsiSelect.addEventListener("change", function () {
            kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
            (kabupatenData[this.value] || []).forEach(k =>
                kabupatenSelect.insertAdjacentHTML("beforeend", `<option value="${k}">${k}</option>`)
            );
        });

        // kalau user sebelumnya sudah pilih provinsi + kabupaten (old value)
        const oldProv = "{{ old('provinsi') }}";
        const oldKab  = "{{ old('kabupaten') }}";
        if (oldProv && kabupatenData[oldProv]) {
            kabupatenData[oldProv].forEach(k => {
                kabupatenSelect.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${k}" ${k === oldKab ? 'selected' : ''}>${k}</option>`
                );
            });
        }
    }

    // ====================== MULTI IMAGE PREVIEW + DELETE ======================
    const inputFoto = document.getElementById("foto_barang");
    const previewContainer = document.getElementById("preview-container");

    const placeholderTemplate = previewContainer.innerHTML;
    let selectedFiles = [];

    // klik di area dropzone -> buka file picker
    previewContainer.addEventListener("click", () => inputFoto.click());

    inputFoto.addEventListener("change", (e) => {
        const files = Array.from(e.target.files);

        files.forEach(file => {
            if (selectedFiles.length < 5) selectedFiles.push(file);
        });

        inputFoto.value = "";
        renderPreview();
    });

    function renderPreview() {
        previewContainer.innerHTML = "";

        if (selectedFiles.length === 0) {
            previewContainer.innerHTML = placeholderTemplate;
            return;
        }

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const wrapper = document.createElement("div");
                wrapper.className = "relative";
                wrapper.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-48 object-cover rounded-2xl border border-slate-200 bg-white">
                    <button data-index="${index}"
                        class="absolute top-2 right-2 bg-red-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow">
                        ×
                    </button>
                `;
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        syncInput();
    }

    function syncInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        inputFoto.files = dataTransfer.files;
    }

    previewContainer.addEventListener("click", (e) => {
        if (e.target.tagName === "BUTTON" && e.target.dataset.index !== undefined) {
            selectedFiles.splice(parseInt(e.target.dataset.index), 1);
            renderPreview();
        }
    });

});
</script>
@endpush
