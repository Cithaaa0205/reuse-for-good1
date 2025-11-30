@extends('layouts.app')

@section('title', 'Profil ' . $user->nama_lengkap)

{{-- Tampilkan tombol back jika BUKAN profil kita sendiri --}}
@if(Auth::check() && Auth::id() !== $user->id)
    @section('showBackButton', true)
@endif

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER PROFIL --}}
    <section
        class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-lg"
    >
        {{-- Glow lembut di pojok --}}
        <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_left,_#ffffff,_transparent_55%)]"></div>

        <div class="relative px-6 sm:px-8 py-6 sm:py-7 flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
            {{-- Avatar --}}
            <div class="flex flex-col items-center gap-4">
                @if($user->foto_profil)
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-white/20 blur-md"></div>
                        <img
                            class="relative h-24 w-24 md:h-28 md:w-28 rounded-full object-cover border-[5px] border-white/60 shadow-lg"
                            src="{{ asset('uploads/avatars/' . $user->foto_profil) }}"
                            alt="Foto Profil"
                        >
                    </div>
                @else
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-white/15 blur-md"></div>
                        <div
                            class="relative h-24 w-24 md:h-28 md:w-28 rounded-full bg-blue-500/80 text-white flex items-center justify-center font-bold text-3xl md:text-4xl border-[5px] border-white/60 shadow-lg"
                        >
                            {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $user->nama_lengkap), 0, 2)) }}
                        </div>
                    </div>
                @endif

                {{-- Badge role --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/90 text-[11px] font-semibold tracking-wide text-blue-700 shadow-sm">
                    <i data-lucide="gift" class="w-3 h-3"></i>
                    Donatur & Penerima
                </span>
            </div>

            {{-- Info utama --}}
            <div class="flex-1 w-full space-y-3">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                    <div class="space-y-1 text-center md:text-left">
                        <h1 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                            {{ $user->nama_lengkap }}
                        </h1>
                        <p class="text-xs sm:text-sm text-blue-50/95">
                            @<span>{{ $user->username }}</span>
                            @if($user->email)
                                • {{ $user->email }}
                            @endif
                            • Bergabung {{ $user->created_at->isoFormat('MMMM YYYY') }}
                        </p>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="flex flex-wrap justify-center md:justify-end gap-2">
                        @auth
                            @if(Auth::id() == $user->id)
                                <a href="{{ route('profile.edit') }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-white text-blue-700 shadow-md hover:bg-blue-50 transition">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    Edit Profil
                                </a>
                            @else
                                <a href="{{ route('chat.show', $user->id) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-white/95 text-blue-700 shadow-md hover:bg-white transition">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    Kirim Pesan
                                </a>

                                {{-- Tombol Laporkan User --}}
                                <button
                                    type="button"
                                    onclick="openReportModal(
                                        '{{ route('report.user', $user->id) }}',
                                        {
                                            title: 'Laporkan Pengguna',
                                            subtitle: 'Laporkan jika akun ini terindikasi spam, penipuan, atau melanggar aturan komunitas.'
                                        }
                                    )"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs sm:text-sm font-semibold bg-rose-50 text-rose-700 border border-rose-100 shadow-sm hover:bg-rose-100 transition"
                                >
                                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                    Laporkan
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Chip statistik --}}
                <div class="bg-white/15 rounded-2xl px-3 py-3 flex flex-wrap justify-center md:justify-start gap-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-slate-700 border border-white/80 shadow-sm text-xs sm:text-sm">
                        <i data-lucide="package" class="w-4 h-4 text-blue-500"></i>
                        <span><span class="font-semibold">{{ $barangDonasi->count() }}</span> barang didonasikan</span>
                    </div>

                    @if(Auth::check() && Auth::id() == $user->id)
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-slate-700 border border-white/80 shadow-sm text-xs sm:text-sm">
                            <i data-lucide="inbox" class="w-4 h-4 text-emerald-500"></i>
                            <span><span class="font-semibold">{{ $barangDiterima->count() }}</span> barang diterima</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white text-slate-700 border border-white/80 shadow-sm text-xs sm:text-sm">
                            <i data-lucide="heart" class="w-4 h-4 text-rose-500"></i>
                            <span><span class="font-semibold">{{ $favorites->count() }}</span> favorit</span>
                        </div>
                    @endif
                </div>

                {{-- Bio --}}
                <p class="text-xs sm:text-sm text-blue-50/95 mt-1.5 max-w-2xl text-center md:text-left">
                    {{ $user->deskripsi ?? 'Pengguna ini belum menambahkan deskripsi.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- TAB + KONTEN --}}
    <div x-data="{ activeTab: 'donasi' }" x-cloak class="space-y-4">

        {{-- NAV TAB --}}
        <div class="bg-white/80 rounded-2xl border border-slate-200 shadow-sm px-3 sm:px-4 py-3">
            <div class="flex flex-wrap gap-2">
                <button
                    @click="activeTab = 'donasi'"
                    :class="activeTab === 'donasi'
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                    class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold flex items-center gap-2 transition"
                >
                    <i data-lucide="gift" class="w-4 h-4"></i>
                    Barang Didonasikan
                </button>

                @if(Auth::check() && Auth::id() == $user->id)
                    <button
                        @click="activeTab = 'diterima'"
                        :class="activeTab === 'diterima'
                            ? 'bg-blue-600 text-white shadow-sm'
                            : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold flex items-center gap-2 transition"
                    >
                        <i data-lucide="inbox" class="w-4 h-4"></i>
                        Barang Diterima
                    </button>

                    <button
                        @click="activeTab = 'favorit'"
                        :class="activeTab === 'favorit'
                            ? 'bg-blue-600 text-white shadow-sm'
                            : 'bg-slate-50 text-slate-700 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-full text-xs sm:text-sm font-semibold flex items-center gap-2 transition"
                    >
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        Favorit
                    </button>
                @endif
            </div>
        </div>

        {{-- TAB 1: BARANG DIDONASIKAN --}}
        <section x-show="activeTab === 'donasi'">
            @if($barangDonasi->isEmpty())
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-6 py-10 text-center text-slate-500">
                    <i data-lucide="package-x" class="w-12 h-12 mx-auto text-slate-300 mb-4"></i>
                    <p class="text-sm">
                        {{ $user->nama_lengkap }} belum mendonasikan barang.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($barangDonasi as $item)
                        <a href="{{ route('barang.show', $item->id) }}"
                           class="group bg-white/95 rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden flex flex-col">
                            @if($item->foto_barang_utama)
                                <div class="relative">
                                    <img
                                        src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                        alt="{{ $item->nama_barang }}"
                                        class="w-full h-32 md:h-40 object-cover"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                    <span
                                        class="absolute top-2 left-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-white/90 text-slate-700 shadow-sm"
                                    >
                                        Donasi
                                    </span>
                                </div>
                            @else
                                <div class="w-full h-32 md:h-40 bg-slate-100 flex items-center justify-center">
                                    <i data-lucide="image-off" class="w-7 h-7 text-slate-400"></i>
                                </div>
                            @endif

                            <div class="p-3 flex flex-col gap-1 flex-1">
                                <h4 class="font-semibold text-xs sm:text-sm text-slate-900 line-clamp-2">
                                    {{ $item->nama_barang }}
                                </h4>
                                <p class="text-[11px] text-slate-500 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    <span class="truncate">{{ $item->lokasi }}</span>
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        @if(Auth::check() && Auth::id() == $user->id)
            {{-- TAB 2: BARANG DITERIMA --}}
            <section x-show="activeTab === 'diterima'" style="display: none;">
                @if($barangDiterima->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-6 py-10 text-center text-slate-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-300 mb-4"></i>
                        <p class="text-sm">Anda belum menerima barang donasi.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($barangDiterima as $item)
                            <a href="{{ route('barang.show', $item->id) }}"
                               class="group bg-white/95 rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden flex flex-col">
                                @if($item->foto_barang_utama)
                                    <div class="relative">
                                        <img
                                            src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                            alt="{{ $item->nama_barang }}"
                                            class="w-full h-32 md:h-40 object-cover"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                        <span
                                            class="absolute top-2 left-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100"
                                        >
                                            Diterima
                                        </span>
                                    </div>
                                @else
                                    <div class="w-full h-32 md:h-40 bg-slate-100 flex items-center justify-center">
                                        <i data-lucide="image-off" class="w-7 h-7 text-slate-400"></i>
                                    </div>
                                @endif

                                <div class="p-3 flex flex-col gap-1 flex-1">
                                    <h4 class="font-semibold text-xs sm:text-sm text-slate-900 line-clamp-2">
                                        {{ $item->nama_barang }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        <span class="truncate">{{ $item->lokasi }}</span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- TAB 3: FAVORIT --}}
            <section x-show="activeTab === 'favorit'" style="display: none;">
                @if($favorites->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm px-6 py-10 text-center text-slate-500">
                        <i data-lucide="heart-off" class="w-12 h-12 mx-auto text-slate-300 mb-4"></i>
                        <p class="text-sm">Anda belum mem-favoritkan barang apa pun.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($favorites as $item)
                            <div class="group bg-white/95 rounded-3xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden relative flex flex-col">
                                <a href="{{ route('barang.show', $item->id) }}">
                                    @if($item->foto_barang_utama)
                                        <div class="relative">
                                            <img
                                                src="{{ asset('uploads/barang/' . $item->foto_barang_utama) }}"
                                                alt="{{ $item->nama_barang }}"
                                                class="w-full h-32 md:h-40 object-cover"
                                            >
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                                        </div>
                                    @else
                                        <div class="w-full h-32 md:h-40 bg-slate-100 flex items-center justify-center">
                                            <i data-lucide="image-off" class="w-7 h-7 text-slate-400"></i>
                                        </div>
                                    @endif

                                    <div class="p-3 flex flex-col gap-1 flex-1">
                                        <h4 class="font-semibold text-xs sm:text-sm text-slate-900 line-clamp-2">
                                            {{ $item->nama_barang }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            <span class="truncate">{{ $item->lokasi }}</span>
                                        </p>
                                        <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            <span>{{ $item->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                </a>

                                {{-- Tombol un-favorite --}}
                                <form
                                    action="{{ route('favorite.toggle', $item->id) }}"
                                    method="POST"
                                    class="absolute top-2 right-2"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="p-1.5 rounded-full bg-white/95 hover:bg-white text-red-500 shadow-sm transition"
                                    >
                                        <i data-lucide="heart" class="w-4 h-4 fill-current"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    </div>
</div>

{{-- MODAL LAPORAN (dipakai untuk lapor user dari profil ini) --}}
<div id="report-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-report-close></div>

    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-auto p-5 sm:p-6 border border-slate-100">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-500" id="report-modal-label">
                    Laporkan Pengguna
                </p>
                <h2 class="text-lg font-bold text-slate-900" id="report-modal-title">
                    Laporkan
                </h2>
                <p class="text-xs text-slate-500 mt-1" id="report-modal-subtitle">
                    Beri tahu admin jika kamu menemukan aktivitas mencurigakan dari pengguna ini.
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
                        $reportReasons = [
                            'Spam / promosi berlebihan',
                            'Penipuan / meminta data pribadi / pembayaran',
                            'Mengirim pesan yang tidak pantas',
                            'Profil mencurigakan / info palsu',
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
                    Laporanmu bersifat rahasia dan hanya akan digunakan untuk menjaga keamanan platform.
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

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reportModal = document.getElementById('report-modal');
            if (!reportModal) return;

            const form        = document.getElementById('report-modal-form');
            const reasonInput = document.getElementById('report-modal-reason');
            const titleEl     = document.getElementById('report-modal-title');
            const subtitleEl  = document.getElementById('report-modal-subtitle');

            window.openReportModal = function (action, options = {}) {
                form.action            = action;
                titleEl.textContent    = options.title || 'Laporkan';
                subtitleEl.textContent = options.subtitle || 'Beri tahu admin jika ada hal yang mencurigakan atau tidak pantas.';
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
        });
    </script>
@endpush
@endsection
