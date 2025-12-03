@extends('layouts.app')

@section('title', 'Etalase Barang Donasi')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- ANIMASI GLOBAL HALAMAN INI --}}
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
            animation: heroFadeUp .6s ease-out forwards;
        }

        .hero-animate-delay-1 { animation-delay: .08s; }
        .hero-animate-delay-2 { animation-delay: .16s; }
        .hero-animate-delay-3 { animation-delay: .24s; }
        .hero-animate-delay-4 { animation-delay: .32s; }

        @keyframes heroOrbFloat {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
            }
            50% {
                transform: translate3d(18px, -12px, 0) scale(1.06);
            }
        }

        .hero-orb-1 {
            animation: heroOrbFloat 20s ease-in-out infinite;
        }

        .hero-orb-2 {
            animation: heroOrbFloat 26s ease-in-out infinite alternate;
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

        @keyframes modalFade {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes modalScale {
            from {
                opacity: 0;
                transform: translateY(12px) scale(.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-show {
            display: flex !important;
            animation: modalFade .18s ease-out;
        }

        .modal-content-show {
            animation: modalScale .22s ease-out;
        }
    </style>

    {{-- HERO / HEADER --}}
    <section
        class="relative overflow-hidden rounded-[28px] bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-[0_22px_55px_rgba(15,23,42,0.35)]">
        {{-- glow & orb dekoratif --}}
        <div class="pointer-events-none absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_left,_#ffffff,_transparent_55%)]"></div>
        <div class="pointer-events-none hero-orb-1 absolute -bottom-10 -right-10 w-48 h-48 rounded-full border border-white/30 opacity-40"></div>
        <div class="pointer-events-none hero-orb-2 absolute -top-12 right-1/3 w-32 h-32 rounded-full border border-white/20 opacity-30"></div>

        <div class="relative px-6 sm:px-8 py-6 sm:py-7 lg:py-8 flex flex-col lg:flex-row lg:items-center gap-5">
            <div class="flex-1 space-y-2">
                <p class="hero-animate text-[11px] tracking-[0.16em] font-semibold uppercase text-blue-100">
                    Etalase Barang Donasi
                </p>

                <h1 class="hero-animate hero-animate-delay-1 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">
                    Temukan barang donasi terbaik di sekitarmu 🌱
                </h1>

                <p class="hero-animate hero-animate-delay-2 text-xs sm:text-sm text-blue-50/90 max-w-xl">
                    Barang-barang layak pakai dari para donatur kami tampilkan dengan prioritas lokasi dan waktu
                    posting. Atur lokasi utamamu di profil untuk rekomendasi yang lebih relevan dan dekat denganmu.
                </p>

                {{-- Info lokasi akun --}}
                @if($userLocationLabel)
                    <div
                        class="hero-animate hero-animate-delay-3 inline-flex items-center gap-2 mt-2 px-3 py-1.5 rounded-full bg-white/15 text-[11px] sm:text-xs backdrop-blur border border-white/25">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        <span class="font-medium">Lokasi akun:</span>
                        <span class="font-semibold">{{ $userLocationLabel }}</span>
                    </div>
                @else
                    <div
                        class="hero-animate hero-animate-delay-3 inline-flex items-center gap-2 mt-2 px-3 py-1.5 rounded-full bg-white/15 text-[11px] sm:text-xs backdrop-blur border border-white/25">
                        <i data-lucide="map-pin-off" class="w-3.5 h-3.5"></i>
                        <span class="font-medium">Lokasi akun belum diatur.</span>
                        <a href="{{ route('lokasi.create') }}"
                           class="underline-offset-2 underline font-semibold">
                            Atur sekarang
                        </a>
                    </div>
                @endif
            </div>

            <div class="hero-animate hero-animate-delay-4 flex flex-col items-start lg:items-end gap-3">
                <a href="{{ route('barang.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold
                          bg-white text-blue-700 shadow-md hover:shadow-lg hover:-translate-y-[1px] transition">
                    <i data-lucide="gift" class="w-4 h-4"></i>
                    Donasikan Barang
                </a>

                {{-- TOMBOL FILTER LOKASI --}}
                <button type="button" id="btn-open-location-filter"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium
                               bg-blue-500/40 text-blue-50 border border-blue-100/60 hover:bg-blue-600/60 hover:border-blue-200 transition">
                    <i data-lucide="locate-fixed" class="w-3.5 h-3.5"></i>
                    Filter Lokasi Barang Donasi
                </button>
            </div>
        </div>
    </section>

    {{-- SEARCH + FILTER INFO --}}
    <section
        class="rounded-[26px] bg-gradient-to-r from-white via-sky-50/60 to-white border border-slate-200/70
               shadow-[0_22px_55px_rgba(15,23,42,0.08)] p-4 sm:p-5 space-y-4">
        <form action="{{ route('barang.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            {{-- Input Search --}}
            <div class="relative flex-1">
                <i data-lucide="search"
                   class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang, kategori, atau lokasi…"
                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-700
                           bg-white/80 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400
                           transition"
                >
            </div>

            {{-- dropdown kategori cepat --}}
            <div class="md:w-44">
                <select name="kategori"
                        class="w-full px-3 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-700
                               bg-white/90 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    <option value="">Semua kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->slug }}"
                            {{ request('kategori') === $kategori->slug ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Submit --}}
            <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl text-sm font-semibold
                           bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg transition">
                <i data-lucide="filter" class="w-4 h-4 mr-1.5"></i>
                Terapkan
            </button>
        </form>

        {{-- Info filter aktif --}}
        <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] sm:text-xs text-slate-500">
            <div class="flex flex-wrap gap-2 items-center">
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50/80 border border-slate-200">
                    <i data-lucide="info" class="w-3 h-3"></i>
                    @if($isSearchActive)
                        Menampilkan barang sesuai pencarian & filter.
                    @else
                        Menampilkan rekomendasi berdasarkan lokasi dan waktu posting.
                    @endif
                </span>

                @if(request('search'))
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-600">
                        <i data-lucide="search" class="w-3 h-3"></i>
                        Kata kunci: <span class="font-semibold">{{ request('search') }}</span>
                    </span>
                @endif

                @if(request('kategori'))
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600">
                        <i data-lucide="tag" class="w-3 h-3"></i>
                        Kategori: <span class="font-semibold">{{ request('kategori') }}</span>
                    </span>
                @endif

                @if(request('filter_provinsi') || request('filter_kabupaten'))
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-50 border border-purple-200 text-purple-700">
                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                        Lokasi filter:
                        <span class="font-semibold">
                            {{ request('filter_kabupaten') ? request('filter_kabupaten').',' : '' }}
                            {{ request('filter_provinsi') }}
                        </span>
                    </span>
                @endif
            </div>

            @if(request()->hasAny(['search','kategori','filter_provinsi','filter_kabupaten']))
                <a href="{{ route('barang.index') }}"
                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-600">
                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                    Reset filter
                </a>
            @endif
        </div>
    </section>

    {{-- KATEGORI BAR --}}
    <section class="space-y-3">
        <div class="flex items-center justify-between gap-2">
            <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">
                Kategori
            </h2>
        </div>

        <div class="flex gap-2 overflow-x-auto pb-2">
            <a href="{{ url('/barang') }}"
               class="whitespace-nowrap px-4 py-2 rounded-full text-xs sm:text-sm font-medium
                      {{ request('kategori')
                            ? 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200/70 transition'
                            : 'bg-blue-600 text-white border border-blue-600 shadow-md hover:shadow-lg transition' }}">
                Semua
            </a>

            @foreach ($kategoris as $kategori)
                <a href="{{ url('/barang?kategori=' . $kategori->slug) }}"
                   class="whitespace-nowrap px-4 py-2 rounded-full text-xs sm:text-sm font-medium border
                          {{ request('kategori') === $kategori->slug
                                ? 'bg-blue-600 text-white border-blue-600 shadow-md hover:shadow-lg transition'
                                : 'bg-white text-slate-600 border-slate-200 shadow-sm hover:bg-slate-50 transition' }}">
                    {{ $kategori->nama_kategori }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- JUDUL REKOMENDASI --}}
    <div class="flex items-center justify-between gap-2">
        <h2 class="text-lg sm:text-xl font-semibold text-slate-900">
            {{ $isSearchActive ? 'Hasil Pencarian' : 'Rekomendasi untuk Anda' }}
        </h2>
        <span class="text-xs text-slate-500">
            {{ $barang->total() }} barang ditemukan
        </span>
    </div>

    {{-- GRID BARANG --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($barang as $item)
            @php
                $fotoLain   = $item->foto_barang_lainnya ? json_decode($item->foto_barang_lainnya, true) : [];
                $totalFoto  = ($item->foto_barang_utama ? 1 : 0) + count($fotoLain);
                $delay      = 0.03 * $loop->index;
            @endphp

            <a href="{{ route('barang.show', $item->id) }}"
               class="card-animate group bg-white/95 rounded-3xl border border-slate-200 shadow-md hover:shadow-[0_24px_60px_rgba(15,23,42,0.16)] hover:-translate-y-1 transition overflow-hidden flex flex-col"
               style="animation-delay: {{ $delay }}s">

                {{-- FOTO --}}
                <div class="relative">
                    <img
                        src="{{ $item->foto_barang_utama ? asset('uploads/barang/'.$item->foto_barang_utama) : 'https://placehold.co/300x200/f3f4f6?text=No+Image' }}"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='https://placehold.co/400x300/f3f4f6/a1a1aa?text=No+Image';"
                        alt="{{ $item->nama_barang }}"
                        class="w-full h-40 object-cover object-center rounded-t-3xl">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition"></div>

                    <div class="absolute top-2 left-2 flex flex-wrap gap-1.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-white/90 text-slate-700 border border-slate-200 shadow-sm">
                            {{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                        </span>
                    </div>

                    @if($totalFoto > 1)
                        <div class="absolute bottom-2 right-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-black/55 text-white text-[10px] backdrop-blur">
                                <i data-lucide="images" class="w-3 h-3"></i>
                                {{ $totalFoto }} foto
                            </span>
                        </div>
                    @endif
                </div>

                {{-- KONTEN --}}
                <div class="p-4 flex flex-col gap-1 flex-1">
                    <p class="font-semibold text-sm text-slate-900 line-clamp-2">
                        {{ $item->nama_barang }}
                    </p>

                    <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1">
                        <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                        <span class="truncate">
                            {{ $item->kabupaten ?? '-' }}, {{ $item->provinsi ?? '-' }}
                        </span>
                    </p>

                    @if(isset($item->distance))
                        <p class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                            <i data-lucide="navigation" class="w-3 h-3"></i>
                            {{ number_format($item->distance, 1) }} km dari lokasi Anda
                        </p>
                    @endif

                    <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-50 border border-slate-200">
                            <i data-lucide="recycle" class="w-3 h-3"></i>
                            <span>Donasi</span>
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div
                class="col-span-full bg-amber-50 border border-amber-200 rounded-2xl px-4 py-5 flex gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-amber-800">
                        Tidak ada barang ditemukan pada kategori atau pencarian ini.
                    </p>
                    <p class="text-xs text-amber-700 mt-1">
                        Coba ubah kata kunci, kategori, atau filter lokasi untuk melihat hasil lain.
                    </p>
                </div>
            </div>
        @endforelse
    </section>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $barang->withQueryString()->links() }}
    </div>

</div>



{{-- MODAL FILTER LOKASI --}}
<div id="location-filter-modal"
     class="fixed inset-0 z-40 hidden items-center justify-center bg-slate-900/30 backdrop-blur-sm px-4">
    <div
        class="modal-inner max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-5 sm:p-6 relative">
        <button type="button" id="btn-close-location-filter"
                class="absolute top-3 right-3 text-slate-400 hover:text-slate-600">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>

        <h3 class="text-base sm:text-lg font-semibold text-slate-900 mb-1.5">
            Filter Lokasi Tampilan
        </h3>
        <p class="text-[11px] sm:text-xs text-slate-500 mb-4">
            Filter ini hanya mempengaruhi daftar barang di halaman ini dan
            <span class="font-semibold">tidak mengubah lokasi utama di profil.</span>
        </p>

        <form action="{{ route('barang.index') }}" method="GET" class="space-y-3">
            {{-- pertahankan search & kategori yg sedang aktif --}}
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">

            <div class="space-y-1.5">
                <label class="text-xs font-medium text-slate-700">
                    Provinsi
                </label>
                <select id="filter_provinsi" name="filter_provinsi"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2
                               focus:ring-blue-200 focus:border-blue-400 transition">
                    <option value="">Semua provinsi</option>
                    @php
                        $provOptions = ['DI Yogyakarta', 'Jawa Tengah', 'Jawa Barat', 'Jawa Timur'];
                        $selectedFilterProv = request('filter_provinsi');
                    @endphp
                    @foreach($provOptions as $prov)
                        <option value="{{ $prov }}"
                            {{ $selectedFilterProv === $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-medium text-slate-700">
                    Kabupaten/Kota
                </label>
                <select id="filter_kabupaten" name="filter_kabupaten"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2
                               focus:ring-blue-200 focus:border-blue-400 transition">
                    <option value="">Semua kabupaten/kota</option>
                </select>
            </div>

            <div class="flex items-center justify-between gap-2 pt-2">
                <button type="button"
                        onclick="window.location='{{ route('barang.index', array_filter(request()->except(['filter_provinsi','filter_kabupaten']))) }}'"
                        class="px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100">
                    Hapus filter lokasi
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-2xl text-xs sm:text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>



{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    // --- Modal open/close ---
    const modal      = document.getElementById("location-filter-modal");
    const modalInner = modal?.querySelector(".modal-inner");
    const btnOpen    = document.getElementById("btn-open-location-filter");
    const btnClose   = document.getElementById("btn-close-location-filter");

    function openModal() {
        if (!modal) return;
        modal.classList.add("modal-show");
        if (modalInner) {
            modalInner.classList.add("modal-content-show");
        }
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove("modal-show");
        if (modalInner) {
            modalInner.classList.remove("modal-content-show");
        }
    }

    if (btnOpen)  btnOpen.addEventListener("click", openModal);
    if (btnClose) btnClose.addEventListener("click", closeModal);

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModal();
        });
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeModal();
    });

    // --- Dynamic kabupaten berdasarkan provinsi (contoh sederhana) ---
    const provSelect = document.getElementById("filter_provinsi");
    const kabSelect  = document.getElementById("filter_kabupaten");

    if (provSelect && kabSelect) {
        const kabOptionsData = {
            "DI Yogyakarta": [
                "Kota Yogyakarta",
                "Sleman",
                "Bantul",
                "Gunungkidul",
                "Kulon Progo",
            ],
            "Jawa Tengah": [
                "Kota Semarang",
                "Kab. Semarang",
                "Surakarta",
                "Magelang",
                "Purwokerto",
            ],
            "Jawa Barat": [
                "Kota Bandung",
                "Kab. Bandung",
                "Bogor",
                "Depok",
                "Bekasi",
            ],
            "Jawa Timur": [
                "Kota Surabaya",
                "Sidoarjo",
                "Gresik",
                "Malang",
                "Kediri",
            ],
        };

        function renderKabupatenOptions() {
            const selectedProv  = provSelect.value;
            const currentKabVal = "{{ request('filter_kabupaten') }}";

            kabSelect.innerHTML = '<option value="">Semua kabupaten/kota</option>';

            if (!selectedProv || !kabOptionsData[selectedProv]) return;

            kabOptionsData[selectedProv].forEach((kab) => {
                const opt    = document.createElement("option");
                opt.value    = kab;
                opt.textContent = kab;
                if (kab === currentKabVal) opt.selected = true;
                kabSelect.appendChild(opt);
            });
        }

        provSelect.addEventListener("change", () => {
            // saat user ganti provinsi di modal, kabupaten direset
            "{{ request('filter_kabupaten') }}" // hanya supaya blade tetap happy
            renderKabupatenOptions();
        });

        // render awal saat modal dibuka (untuk kasus provinsi sudah terpilih)
        renderKabupatenOptions();
    }
});
</script>

@endsection
