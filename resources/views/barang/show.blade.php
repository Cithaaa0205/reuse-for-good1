@extends('layouts.app')

@section('title', $barang->nama_barang)
@section('showBackButton', true)

@section('content')
@php
    // Susun lokasi seperti contoh: (Jawa Tengah, Surakarta)
    $namaProvinsi  = $barang->provinsi ?? '-';
    $namaKabupaten = $barang->kabupaten ?? '-';
    $lokasiDisplay = $namaProvinsi . ', ' . $namaKabupaten;

    // Decode foto lain
    $fotoLain = $barang->foto_barang_lainnya ? json_decode($barang->foto_barang_lainnya, true) : [];

    // Path gambar utama (kalau ada)
    $mainImageSrc = $barang->foto_barang_utama
        ? asset('uploads/barang/' . $barang->foto_barang_utama)
        : 'https://placehold.co/800x600?text=No+Image';
@endphp

<main class="max-w-7xl mx-auto space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <p class="text-xs uppercase tracking-wide text-slate-400 mb-1">Detail Barang Donasi</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $barang->nama_barang }}</h1>

            <div class="mt-2 flex flex-wrap gap-2 items-center text-[11px] text-slate-500">
                {{-- Kategori --}}
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-semibold">
                    <i data-lucide="tag" class="w-3 h-3"></i>
                    {{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                </span>

                {{-- Lokasi (Provinsi, Kabupaten) --}}
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    {{ $lokasiDisplay }}
                </span>

                {{-- Waktu posting --}}
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 border border-slate-200">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    Diposting {{ $barang->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        @auth
            <div class="flex flex-wrap gap-2 justify-start sm:justify-end">
                {{-- Laporkan barang --}}
                <button
                    type="button"
                    onclick="openReportModal(
                        '{{ route('report.barang', $barang->id) }}',
                        {
                            title: 'Laporkan Barang',
                            subtitle: 'Laporkan jika barang ini tidak layak pakai, tidak sesuai deskripsi, atau berpotensi membahayakan penerima.'
                        }
                    )"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100 hover:bg-rose-100 transition"
                >
                    <i data-lucide="shield-alert" class="w-3 h-3"></i>
                    Laporkan Barang
                </button>

                {{-- Laporkan pendonasi (user) --}}
                @if($barang->donatur)
                    <button
                        type="button"
                        onclick="openReportModal(
                            '{{ route('report.user', $barang->donatur->id) }}',
                            {
                                title: 'Laporkan Pendonasi',
                                subtitle: 'Gunakan fitur ini jika akun terasa mencurigakan, melakukan penipuan, atau melanggar aturan platform.'
                            }
                        )"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-semibold bg-slate-900 text-slate-50 hover:bg-slate-800 shadow-sm transition"
                    >
                        <i data-lucide="user-x" class="w-3 h-3"></i>
                        Laporkan User
                    </button>
                @endif
            </div>
        @endauth
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-8 items-start">

        {{-- ================= Gambar ================= --}}
        <div class="space-y-4">
            {{-- Gambar utama --}}
            <div class="relative rounded-3xl overflow-hidden bg-slate-100 shadow-[0_22px_55px_rgba(15,23,42,0.15)]">
                <img
                    id="mainImage"
                    src="{{ $mainImageSrc }}"
                    alt="{{ $barang->nama_barang }}"
                    class="w-full aspect-[4/3] object-cover"
                    onerror="this.onerror=null;this.src='https://placehold.co/800x600?text=No+Image';"
                >

                {{-- Overlay gradient halus --}}
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>

                {{-- Badge status --}}
                <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-semibold
                                 {{ $barang->status === 'Tersedia'
                                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                        : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                        {{ $barang->status ?? 'Tersedia' }}
                    </span>
                </div>

                {{-- Lokasi di atas gambar --}}
                <div class="absolute bottom-3 left-3">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-medium
                                 bg-white/95 text-slate-800 border border-slate-200 shadow-sm">
                        <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                        {{ $lokasiDisplay }}
                    </span>
                </div>
            </div>

            {{-- Thumbnail --}}
            @if($barang->foto_barang_utama || count($fotoLain) > 0)
                <div class="flex gap-3 mt-1 overflow-x-auto pb-1">
                    @if($barang->foto_barang_utama)
                        <img
                            src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                            class="thumb w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border-2 border-blue-600 cursor-pointer object-cover bg-slate-100"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';"
                        >
                    @endif

                    @foreach($fotoLain as $foto)
                        <img
                            src="{{ asset('uploads/barang/' . $foto) }}"
                            class="thumb w-20 h-20 sm:w-24 sm:h-24 rounded-2xl border cursor-pointer object-cover bg-slate-100 hover:border-blue-300 transition"
                            onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';"
                        >
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ================= DETAIL & AKSI ================= --}}
        <div class="space-y-5">

            {{-- Detail barang --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 space-y-4">
                <div class="space-y-2 text-sm text-slate-700">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">
                        Deskripsi
                    </h3>
                    <p class="leading-relaxed">
                        {{ $barang->deskripsi }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Kondisi</p>
                        <p class="font-semibold text-slate-900">
                            {{ $barang->kondisi ?? '-' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Lokasi</p>
                        <p class="font-semibold text-slate-900 flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-4 h-4 text-rose-500"></i>
                            <span>{{ $lokasiDisplay }}</span>
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">Jenis Donasi</p>
                        <p class="font-semibold text-slate-900 flex items-center gap-1">
                            <i data-lucide="recycle" class="w-4 h-4 text-emerald-500"></i>
                            <span>Barang Bekas Layak Pakai</span>
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-xs text-slate-400 uppercase tracking-wide">ID Donasi</p>
                        <p class="font-mono text-xs text-slate-600">
                            #BRG{{ str_pad($barang->id, 5, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Donatur --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ $barang->donatur ? route('profile.show', $barang->donatur->username) : '#' }}"
                       class="flex items-center gap-4 flex-1">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-600 to-sky-500
                                    flex items-center justify-center text-white font-bold text-sm sm:text-lg">
                            {{ strtoupper(substr($barang->donatur->nama_lengkap ?? 'User', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $barang->donatur->nama_lengkap ?? 'Pengguna Tidak Diketahui' }}
                            </p>
                            <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                <i data-lucide="gift" class="w-3 h-3"></i>
                                <span>{{ $barang->donatur ? $barang->donatur->barangDonasis->count() : 0 }} donasi telah dibuat</span>
                            </p>
                        </div>
                    </a>
                </div>

                @auth
                    @if(Auth::id() !== $barang->donatur_id)
                        <a href="{{ route('chat.show', $barang->donatur->id) }}"
                           class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-2xl text-sm font-semibold
                                  bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            Hubungi Pendonasi
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Tombol aksi / status permintaan --}}
            <div class="bg-white/90 rounded-3xl border border-slate-200 shadow-soft p-5 sm:p-6 space-y-3">
                @if(Auth::check())
                    {{-- Jika pendonasi --}}
                    @if(Auth::id() == $barang->donatur_id)
                        <div class="text-center space-y-3">
                            <p class="text-sm text-slate-600">
                                Ini adalah donasi milik kamu. Kamu bisa menghapusnya jika sudah tidak tersedia.
                            </p>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus donasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                               bg-red-600 text-white hover:bg-red-700 shadow-md">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                    Hapus Donasi Ini
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Bukan pendonasi --}}
                        @if($requestStatus === 'Diajukan')
                            <div class="bg-amber-50 border border-amber-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-amber-800 flex gap-2">
                                <i data-lucide="hourglass" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Permintaan penerimaan barang sudah diajukan dan sedang menunggu konfirmasi dari pendonasi.
                                </p>
                            </div>
                        @elseif($requestStatus === 'Ditolak')
                            <div class="bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-red-700 flex gap-2">
                                <i data-lucide="x-circle" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Permintaan sebelumnya ditolak. Jika masih membutuhkan barang ini, silakan ajukan kembali.
                                </p>
                            </div>

                            <form action="{{ route('request.store', $barang->id) }}" method="POST" class="pt-1">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                               bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                                    <i data-lucide="refresh-ccw" class="w-4 h-4 mr-2"></i>
                                    Ajukan Ulang Permintaan
                                </button>
                            </form>
                        @elseif($barang->status !== 'Tersedia')
                            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-emerald-800 flex gap-2">
                                <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
                                <p>
                                    Barang ini sudah diterima oleh penerima. Terima kasih sudah mengecek 💚
                                </p>
                            </div>
                        @else
<form action="{{ route('request.store', $barang->id) }}" method="POST" class="space-y-3">
    @csrf
    <textarea name="alasan_permintaan" rows="3"
        class="w-full text-sm border border-slate-300 bg-slate-50 rounded-2xl p-3 focus:ring-2 focus:ring-blue-200 focus:border-blue-500"
        placeholder="Tulis alasan atau pesanmu… Contoh: Sepatu ini untuk lomba sekolah minggu depan 🙏"
        required></textarea>

    <button type="submit"
            class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                   bg-blue-600 text-white hover:bg-blue-700 shadow-md">
        <i data-lucide="hand-heart" class="w-4 h-4 mr-2"></i>
        Ajukan Penerimaan Barang
    </button>
</form>

                        @endif
                    @endif
                @else
                    {{-- Belum login --}}
                    <div class="space-y-3 text-center">
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3 text-xs sm:text-sm text-blue-800">
                            Masuk terlebih dahulu untuk mengajukan penerimaan barang.
                        </div>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl text-sm font-semibold
                                  bg-blue-600 text-white hover:bg-blue-700 shadow-md">
                            <i data-lucide="log-in" class="w-4 h-4 mr-2"></i>
                            Login untuk Mengajukan
                        </a>
                    </div>
                @endif
            </div>

            {{-- Banner keamanan kecil --}}
            <div class="bg-slate-900 text-slate-50 rounded-3xl px-4 py-3 flex flex-col sm:flex-row gap-3 sm:items-center">
                <div class="flex items-start gap-2 text-xs sm:text-sm">
                    <div class="mt-0.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                    </div>
                    <p>
                        Jangan pernah mengirim uang atau data sensitif di luar platform. Laporkan jika ada aktivitas atau barang yang mencurigakan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</main>

{{-- ===================== MODAL LAPORAN (BARANG / USER) ===================== --}}
<div id="report-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-report-close></div>

    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-auto p-5 sm:p-6 border border-slate-100">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-500" id="report-modal-label">
                    Laporkan Konten
                </p>
                <h2 class="text-lg font-bold text-slate-900" id="report-modal-title">
                    Laporkan
                </h2>
                <p class="text-xs text-slate-500 mt-1" id="report-modal-subtitle">
                    Laporkan jika barang ini tidak layak pakai, tidak sesuai deskripsi, atau melanggar aturan platform.
                </p>
            </div>
            <button type="button" class="p-2 rounded-full hover:bg-slate-100 text-slate-400" data-report-close>
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="report-modal-form" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <p class="text-xs font-medium text-slate-700">Pilih alasan cepat</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        // KHUSUS BARANG: alasan difokuskan ke kondisi / kesesuaian barang
                        $reportReasons = [
                            'Barang tidak layak pakai / rusak parah',
                            'Barang tidak sesuai deskripsi',
                            'Foto barang tidak sesuai (real pict berbeda / diambil dari internet)',
                            'Barang berbahaya / melanggar aturan (misal: obat terlarang, senjata, dll)',
                            'Lainnya (jelaskan di kolom di bawah)'
                        ];
                    @endphp
                    @foreach($reportReasons as $reason)
                        <button type="button"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-medium bg-slate-50 text-slate-700 border border-slate-200 hover:border-rose-300 hover:bg-rose-50/70 transition"
                                data-report-reason="{{ $reason }}">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <span class="whitespace-nowrap">{{ $reason }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="space-y-1">
                <label for="report-modal-reason" class="text-xs font-medium text-slate-700">
                    Jelaskan singkat alasanmu <span class="text-rose-500">*</span>
                </label>
                <textarea id="report-modal-reason"
                          name="reason"
                          rows="4"
                          required
                          class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-400 resize-none"></textarea>
                <p class="text-[11px] text-slate-400">
                    Laporanmu bersifat rahasia dan hanya akan digunakan untuk menjaga kenyamanan dan keamanan penerima barang.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button type="button" class="px-3 py-2 rounded-2xl text-xs font-semibold text-slate-500 hover:bg-slate-100 transition" data-report-close>
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-semibold bg-rose-600 text-white shadow-md hover:bg-rose-700 hover:shadow-lg transition">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT: thumbnail + modal laporan --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    // ========= Ganti gambar utama dari thumbnail =========
    const mainImg = document.getElementById("mainImage");
    const thumbs  = document.querySelectorAll(".thumb");

    thumbs.forEach(t => {
        t.addEventListener("click", () => {
            if (!mainImg) return;
            mainImg.src = t.src;
            thumbs.forEach(x => x.classList.remove("border-blue-600"));
            t.classList.add("border-blue-600");
        });
    });

    // ========= Modal Laporan =========
    const reportModal = document.getElementById('report-modal');
    if (reportModal) {
        const form        = document.getElementById('report-modal-form');
        const reasonInput = document.getElementById('report-modal-reason');
        const titleEl     = document.getElementById('report-modal-title');
        const subtitleEl  = document.getElementById('report-modal-subtitle');

        window.openReportModal = function (action, options = {}) {
            form.action            = action;
            titleEl.textContent    = options.title || 'Laporkan';
            subtitleEl.textContent = options.subtitle || 'Laporkan jika barang ini tidak layak pakai, tidak sesuai deskripsi, atau melanggar aturan platform.';
            reasonInput.value      = options.defaultReason || '';
            reportModal.classList.remove('hidden');
            reportModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            reasonInput.focus();
        };

        function closeReportModal() {
            reportModal.classList.add('hidden');
            reportModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        reportModal.querySelectorAll('[data-report-close]').forEach(btn => {
            btn.addEventListener('click', closeReportModal);
        });

        reportModal.addEventListener('click', (e) => {
            if (e.target === reportModal) {
                closeReportModal();
            }
        });

        document.querySelectorAll('[data-report-reason]').forEach(btn => {
            btn.addEventListener('click', () => {
                const reason = btn.getAttribute('data-report-reason');
                if (!reason) return;

                if (!reasonInput.value.trim()) {
                    reasonInput.value = reason;
                } else {
                    const trimmed = reasonInput.value.replace(/\s+$/, '');
                    reasonInput.value = trimmed + (trimmed.endsWith('\n') ? '' : '\n') + reason;
                }
                reasonInput.focus();
            });
        });
    }
});
</script>
@endsection
