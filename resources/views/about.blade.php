@extends('layouts.app')

@section('title', 'Tentang Reuse For Good')
@section('showBackButton', true)

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- HERO / HEADER --}}
    <section
        class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-blue-800 to-sky-600 text-white shadow-xl"
    >
        <div class="absolute inset-0 opacity-40 bg-[radial-gradient(circle_at_top_left,_#38bdf8_0,_transparent_55%),_radial-gradient(circle_at_bottom_right,_#22c55e_0,_transparent_55%)]"></div>

        <div class="relative px-6 sm:px-10 py-10 sm:py-12 flex flex-col sm:flex-row sm:items-center gap-6 sm:gap-10">
            <div class="flex-1 space-y-3">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold tracking-wide uppercase">
                    <i data-lucide="info" class="w-3 h-3"></i>
                    Tentang ReuseForGood
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight">
                    Mengubah Barang Bekas
                    <span class="text-sky-200">Menjadi Harapan Baru</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-100/80 max-w-2xl">
                    ReuseForGood membantu menghubungkan barang bekas layak pakai dengan orang-orang yang benar-benar
                    membutuhkan, dengan cara yang mudah, transparan, dan gratis.
                </p>

                <div class="flex flex-wrap gap-2 pt-2 text-[11px] sm:text-xs text-sky-100/90">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 border border-white/15">
                        <i data-lucide="leaf" class="w-3 h-3"></i>
                        Kurangi limbah, tingkatkan manfaat
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 border border-white/15">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        Menghubungkan donatur & penerima
                    </span>
                </div>
            </div>

            <div class="hidden sm:flex flex-col items-end gap-3">
                <div class="rounded-2xl bg-white/10 backdrop-blur px-4 py-3 text-right shadow-lg border border-white/20">
                    <p class="text-[11px] text-sky-100/90 mb-1">Misi Kami</p>
                    <p class="text-sm font-semibold">Barang bekas yang bermanfaat tak perlu berakhir di tempat sampah.</p>
                </div>
                <div class="flex items-center gap-2 text-[11px] text-sky-100/80">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Berbagi kebaikan, satu barang setiap kali.</span>
                </div>
            </div>
        </div>
    </section>

    {{-- GRID TIGA KOLOM: LOGO, SEJARAH, TIM --}}
    <section class="relative">
        {{-- soft blue glow di belakang grid --}}
        <div class="pointer-events-none absolute inset-x-6 -top-4 -bottom-6 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_60%),_radial-gradient(circle_at_bottom,_rgba(56,189,248,0.16),_transparent_65%)] opacity-70"></div>

        <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- LOGO & DESKRIPSI --}}
            <div
                class="bg-gradient-to-br from-sky-100 via-slate-50 to-emerald-100 rounded-3xl p-[1.5px]
                       shadow-[0_18px_55px_rgba(37,99,235,0.16)] ring-1 ring-sky-100/70"
            >
                <div class="bg-white/95 rounded-[1.4rem] h-full p-6 flex flex-col items-center text-center gap-3">
                    <div class="relative mb-2">
                        <div class="absolute inset-0 rounded-full bg-sky-100 blur-2xl opacity-70"></div>
                        <div class="relative">
                            <img
                                src="{{ asset('foto/Logo.png') }}"
                                alt="Logo RFG"
                                class="w-28 h-28 rounded-full border-4 border-white shadow-xl object-cover"
                            >
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 tracking-tight">REUSEFORGOOD</h3>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Platform Donasi Barang Bekas</p>
                    <p class="text-sm text-slate-600 leading-relaxed mt-1">
                        ReuseForGood adalah platform digital yang menghubungkan pendonasi dengan penerima barang bekas
                        layak pakai. Kami percaya setiap barang memiliki nilai guna yang bisa terus dimanfaatkan
                        daripada berakhir menjadi limbah.
                    </p>
                </div>
            </div>

            {{-- SEJARAH / LATAR BELAKANG --}}
            <div
                class="bg-gradient-to-br from-sky-100 via-indigo-50 to-slate-100 rounded-3xl p-[1.5px]
                       shadow-[0_18px_55px_rgba(59,130,246,0.18)] ring-1 ring-sky-100/70"
            >
                <div class="bg-white/95 rounded-[1.4rem] h-full p-6 flex flex-col gap-3 text-center">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                        <img
                            src="{{ asset('foto/Starpride2.png') }}"
                            alt="Tim ReuseForGood"
                            class="w-full h-40 object-cover"
                        >
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mt-2">Dibentuk pada: 19 Februari 2025</h3>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Latar Belakang</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        ReuseForGood berawal dari tugas Rekayasa Perangkat Lunak dan dikembangkan lebih lanjut
                        pada Proyek Informatika sebagai wujud kepedulian terhadap banyaknya barang layak pakai
                        yang terbuang sia-sia. Kami ingin mempermudah siapa pun untuk berbagi dan menerima bantuan.
                    </p>
                </div>
            </div>

            {{-- TIM KAMI --}}
            <div
                class="bg-gradient-to-br from-violet-100 via-sky-50 to-slate-100 rounded-3xl p-[1.5px]
                       shadow-[0_18px_55px_rgba(129,140,248,0.20)] ring-1 ring-indigo-100/70"
            >
                <div class="bg-white/95 rounded-[1.4rem] h-full p-6 flex flex-col gap-3 text-center">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 shadow-md">
                        <img
                            src="{{ asset('foto/Starpride1.png') }}"
                            alt="Tim ReuseForGood"
                            class="w-full h-40 object-cover"
                        >
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mt-2">Tim Kami</h3>
                    <p class="text-xs uppercase tracking-wide text-slate-400">Mahasiswa Penggerak Kebaikan</p>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        REUSEFORGOOD dikembangkan oleh mahasiswa yang peduli terhadap pemanfaatan barang bekas
                        dan penggunaan teknologi untuk kebaikan sosial. Kami percaya kolaborasi kecil bisa
                        melahirkan dampak yang besar bagi banyak orang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT US – DIPERMANIS --}}
    <section class="space-y-6">
        {{-- Header Contact --}}
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sky-50 via-slate-50 to-emerald-50 border border-slate-100 px-6 sm:px-10 py-8 shadow-md"
        >
            <div class="absolute inset-0 opacity-60 bg-[radial-gradient(circle_at_top,_#e0f2fe,_transparent_60%)] pointer-events-none"></div>

            <div class="relative flex flex-col items-center text-center gap-3">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/80 text-sky-700 text-[11px] font-medium shadow-sm border border-sky-100">
                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                    Mari terhubung dengan tim ReuseForGood
                </span>

                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    Contact Us
                </h2>
                <p class="text-sm sm:text-base text-slate-500 max-w-2xl">
                    Punya saran, pertanyaan, atau ingin berkolaborasi? Gunakan informasi berikut untuk menghubungi kami,
                    atau kirimkan pesan melalui form kontak yang tersedia di platform.
                </p>

                <div class="mt-2">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-sky-700 text-xs sm:text-sm font-semibold shadow-sm border border-sky-100">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Kami senang menerima pesan dari kamu ✨
                    </span>
                </div>
            </div>
        </div>

        {{-- Kartu Kontak --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
            {{-- Website --}}
            <div class="group bg-white/95 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
                <div class="p-5 flex flex-col items-center text-center gap-2">
                    <div class="w-11 h-11 rounded-2xl bg-sky-50 flex items-center justify-center group-hover:bg-sky-100 transition">
                        <i data-lucide="globe" class="w-5 h-5 text-sky-600"></i>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900">Website</h4>
                    <p class="text-[11px] text-slate-400">Kunjungi halaman utama ReuseForGood</p>
                    <a href="#" class="mt-1 text-xs sm:text-sm font-medium text-sky-600 hover:text-sky-700 hover:underline">
                        www.reuseforgood.com
                    </a>
                </div>
            </div>

            {{-- Email --}}
            <div class="group bg-white/95 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
                <div class="p-5 flex flex-col items-center text-center gap-2">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition">
                        <i data-lucide="mail" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900">Email</h4>
                    <p class="text-[11px] text-slate-400">Kirim kritik, saran, atau kerja sama</p>
                    <a href="mailto:hello@reuseforgood.com" class="mt-1 text-xs sm:text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline">
                        hello@reuseforgood.com
                    </a>
                </div>
            </div>

            {{-- Telepon --}}
            <div class="group bg-white/95 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
                <div class="p-5 flex flex-col items-center text-center gap-2">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition">
                        <i data-lucide="phone" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900">Telepon</h4>
                    <p class="text-[11px] text-slate-400">Hubungi kami pada jam kerja</p>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600">
                        123-456-7890
                    </p>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="group bg-white/95 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
                <div class="p-5 flex flex-col items-center text-center gap-2">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition">
                        <i data-lucide="map-pin" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900">Alamat</h4>
                    <p class="text-[11px] text-slate-400">Lokasi operasional ReuseForGood</p>
                    <p class="mt-1 text-xs sm:text-sm text-slate-600">
                        123 Paingan St, Yogyakarta
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
