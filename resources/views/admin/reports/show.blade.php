@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
@php
    use App\Models\Report;

    function statusBadge($status) {
        return match ($status) {
            Report::STATUS_BARU => 'bg-rose-50 text-rose-700 border border-rose-100',
            Report::STATUS_DIPROSES => 'bg-amber-50 text-amber-700 border border-amber-100',
            Report::STATUS_SELESAI => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            default => 'bg-slate-50 text-slate-600 border border-slate-100',
        };
    }

    function statusDot($status) {
        return match ($status) {
            Report::STATUS_BARU => 'bg-rose-500',
            Report::STATUS_DIPROSES => 'bg-amber-500',
            Report::STATUS_SELESAI => 'bg-emerald-500',
            default => 'bg-slate-400',
        };
    }

    function typeLabelDetail($type) {
        return match ($type) {
            Report::TYPE_BARANG => 'Barang donasi',
            Report::TYPE_USER   => 'Pengguna',
            Report::TYPE_PESAN  => 'Pesan chat',
            default             => ucfirst($type ?? '-'),
        };
    }

    // Tentukan user yang bisa dimoderasi dari report ini
    $relatedUser = null;
    if ($report->reported_type === Report::TYPE_USER && $target) {
        $relatedUser = $target;
    } elseif ($report->reported_type === Report::TYPE_BARANG && $target) {
        $relatedUser = $target->donatur ?? null;
    } elseif ($report->reported_type === Report::TYPE_PESAN && $target) {
        $relatedUser = $target->sender ?? null;
    }

    // Untuk target preview label
    $targetTitle = '-';
    if ($report->reported_type === Report::TYPE_BARANG && $target) {
        $targetTitle = $target->nama_barang ?? ('Barang #' . $target->id);
    } elseif ($report->reported_type === Report::TYPE_USER && $target) {
        $targetTitle = $target->nama_lengkap ?? ('User #' . $target->id);
    } elseif ($report->reported_type === Report::TYPE_PESAN && $target) {
        $targetTitle = 'Pesan dari ' . ($target->sender->nama_lengkap ?? 'User #' . $target->sender_id);
    }
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- HEADER + BACK --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.reports.index') }}"
               class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500 hover:text-slate-700 mb-2">
                <i data-lucide="arrow-left" class="w-3 h-3"></i>
                Kembali ke daftar laporan
            </a>

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                Laporan #{{ $report->id }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Dikirim {{ $report->created_at->format('d M Y H:i') }} • Tipe: {{ typeLabelDetail($report->reported_type) }}
            </p>

            <div class="mt-3 inline-flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold {{ statusBadge($report->status) }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ statusDot($report->status) }}"></span>
                    Status: {{ ucfirst($report->status) }}
                </span>

                @if($relatedUser)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-900 text-sky-100 shadow-sm">
                        <i data-lucide="user-shield" class="w-3 h-3"></i>
                        User terkait: {{ $relatedUser->nama_lengkap }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="flex items-start gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- GRID UTAMA --}}
    <div class="grid gap-5 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] items-start">

        {{-- KIRI: DETAIL LAPORAN & TARGET --}}
        <div class="space-y-4">

            {{-- DETAIL LAPORAN --}}
            <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
                <header class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-slate-400 mb-1">
                            Ringkasan laporan
                        </p>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Dari: {{ $report->reporter->nama_lengkap ?? 'User #' . $report->reporter_id }}
                        </h2>
                        <p class="text-[11px] text-slate-400">
                            {{ $report->reporter->email ?? '-' }}
                        </p>
                    </div>
                </header>

                <div class="space-y-2 text-sm">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">
                        Alasan laporan
                    </p>
                    <p class="leading-relaxed text-slate-800 bg-slate-50/80 border border-slate-100 rounded-2xl px-4 py-3">
                        {{ $report->reason }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-500">
                    <div>
                        <p class="uppercase tracking-wide text-[10px] text-slate-400">Dibuat</p>
                        <p class="mt-0.5 font-medium text-slate-800">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="uppercase tracking-wide text-[10px] text-slate-400">Terakhir diupdate</p>
                        <p class="mt-0.5 font-medium text-slate-800">
                            {{ $report->updated_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- TARGET LAPORAN --}}
            <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
                <header class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-slate-400 mb-1">
                            Target laporan
                        </p>
                        <h2 class="text-sm font-semibold text-slate-900">
                            {{ typeLabelDetail($report->reported_type) }}
                        </h2>
                    </div>
                </header>

                @if(!$target)
                    <p class="text-sm text-slate-500">
                        Target laporan sudah tidak ditemukan (mungkin sudah dihapus).
                    </p>
                @else
                    @if($report->reported_type === Report::TYPE_BARANG)
                        {{-- Barang --}}
                        <div class="flex gap-3">
                            @if($target->foto_barang_utama)
                                <img
                                    src="{{ asset('uploads/barang/' . $target->foto_barang_utama) }}"
                                    class="w-16 h-16 rounded-2xl object-cover border border-slate-200"
                                    alt="{{ $target->nama_barang }}"
                                >
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i data-lucide="image-off" class="w-6 h-6"></i>
                                </div>
                            @endif

                            <div class="flex-1 space-y-1">
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $targetTitle }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Oleh: {{ $target->donatur->nama_lengkap ?? 'User #' . $target->donatur_id }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    Lokasi: {{ $target->kabupaten ?? '-' }}, {{ $target->provinsi ?? '-' }}
                                </p>
                                <a href="{{ route('barang.show', $target->id) }}"
                                   class="inline-flex items-center gap-1 mt-1 text-[11px] font-semibold text-blue-600 hover:text-blue-700">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Lihat halaman barang
                                </a>
                            </div>
                        </div>
                    @elseif($report->reported_type === Report::TYPE_USER)
                        {{-- User --}}
                        <div class="flex gap-3 items-center">
                            <div class="h-12 w-12 rounded-full bg-slate-900 text-sky-100 flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $target->nama_lengkap ?? 'U'), 0, 2)) }}
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-sm font-semibold text-slate-900">{{ $target->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500">
                                    @<span>{{ $target->username }}</span> • {{ $target->email }}
                                </p>
                                <a href="{{ route('profile.show', $target->username) }}"
                                   class="inline-flex items-center gap-1 mt-1 text-[11px] font-semibold text-blue-600 hover:text-blue-700">
                                    <i data-lucide="external-link" class="w-3 h-3"></i>
                                    Lihat profil user
                                </a>
                            </div>
                        </div>
                    @elseif($report->reported_type === Report::TYPE_PESAN)
                        {{-- Pesan --}}
                        <div class="space-y-2">
                            <p class="text-xs text-slate-400 uppercase tracking-wide">Cuplikan pesan</p>
                            <p class="text-sm leading-relaxed text-slate-800 bg-slate-50/80 border border-slate-100 rounded-2xl px-4 py-3">
                                {{ $target->message }}
                            </p>
                            <p class="text-xs text-slate-500">
                                Dari:
                                <span class="font-semibold text-slate-800">
                                    {{ $target->sender->nama_lengkap ?? 'User #' . $target->sender_id }}
                                </span>
                                ke
                                <span class="font-semibold text-slate-800">
                                    {{ $target->receiver->nama_lengkap ?? 'User #' . $target->receiver_id }}
                                </span>
                            </p>
                            <p class="text-xs text-slate-400">
                                Dikirim {{ $target->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @endif
                @endif
            </section>

            {{-- RIWAYAT PENANGANAN --}}
            <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-3">
                <header class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Riwayat penanganan
                    </h2>
                </header>

                @if(!$report->handled_by && !$report->admin_notes)
                    <p class="text-sm text-slate-500">
                        Belum ada catatan penanganan dari admin.
                    </p>
                @else
                    <div class="space-y-2 text-sm text-slate-700">
                        @if($report->handled_by)
                            <p class="text-xs text-slate-500">
                                Terakhir ditangani oleh
                                <span class="font-semibold text-slate-800">
                                    {{ $report->handler->nama_lengkap ?? 'Admin #' . $report->handled_by }}
                                </span>
                                pada {{ optional($report->handled_at)->format('d M Y H:i') }}
                            </p>
                        @endif

                        @if($report->admin_notes)
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-xs sm:text-sm whitespace-pre-line">
                                {{ $report->admin_notes }}
                            </div>
                        @endif
                    </div>
                @endif
            </section>
        </div>

        {{-- KANAN: PANEL MODERASI --}}
        <div class="space-y-4">
            {{-- UPDATE STATUS --}}
            <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
                <header class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Update status laporan
                    </h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 text-[10px] text-slate-500 border border-slate-100">
                        <i data-lucide="refresh-ccw" class="w-3 h-3"></i>
                        <span>Perubahan disimpan manual</span>
                    </span>
                </header>

                <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-600">Status laporan</label>
                        <select name="status"
                                class="w-full rounded-2xl border border-slate-200 px-3 py-2 text-xs sm:text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-white">
                            <option value="{{ Report::STATUS_BARU }}" {{ $report->status === Report::STATUS_BARU ? 'selected' : '' }}>Baru</option>
                            <option value="{{ Report::STATUS_DIPROSES }}" {{ $report->status === Report::STATUS_DIPROSES ? 'selected' : '' }}>Sedang diproses</option>
                            <option value="{{ Report::STATUS_SELESAI }}" {{ $report->status === Report::STATUS_SELESAI ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-600">
                            Catatan admin <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea
                            name="admin_notes"
                            rows="3"
                            class="w-full rounded-2xl border border-slate-200 px-3 py-2 text-xs sm:text-sm text-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 bg-slate-50/80"
                            placeholder="Contoh: sudah dicek, user diberikan peringatan, dll.">{{ old('admin_notes', $report->admin_notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-md">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            {{-- MODERASI USER --}}
            @if($relatedUser)
                <section class="bg-white/95 rounded-3xl border border-rose-100 shadow-sm p-5 sm:p-6 space-y-4">
                    <header class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-rose-400 mb-1">
                                Moderasi user terkait
                            </p>
                            <h2 class="text-sm font-semibold text-slate-900">
                                {{ $relatedUser->nama_lengkap }}
                            </h2>
                            <p class="text-[11px] text-slate-500">
                                Status saat ini: <span class="font-semibold text-slate-800">{{ $relatedUser->status }}</span>
                            </p>
                        </div>
                    </header>

                    <form action="{{ route('admin.reports.suspendUser', $report->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-slate-700">
                                Alasan suspend (akan disimpan di user)
                            </label>
                            <textarea
                                name="reason"
                                rows="2"
                                class="w-full rounded-2xl border border-rose-200 px-3 py-2 text-xs sm:text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-400 bg-rose-50/60"
                                placeholder="Contoh: indikasi penipuan dari laporan ini."></textarea>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center w-full gap-1.5 px-4 py-2 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold shadow-md">
                            <i data-lucide="user-x" class="w-4 h-4"></i>
                            Suspend User Berdasarkan Laporan Ini
                        </button>
                    </form>

                    <p class="text-[11px] text-rose-500 mt-1">
                        Suspend: user tidak bisa login sementara sampai status diubah kembali.
                    </p>
                </section>
            @endif

            {{-- MODERASI BARANG --}}
            @if($report->reported_type === Report::TYPE_BARANG && $target)
                <section class="bg-white/95 rounded-3xl border border-amber-100 shadow-sm p-5 sm:p-6 space-y-4">
                    <header class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-amber-500 mb-1">
                                Moderasi barang
                            </p>
                            <h2 class="text-sm font-semibold text-slate-900">
                                {{ $targetTitle }}
                            </h2>
                        </div>
                    </header>

                    <form action="{{ route('admin.reports.hideBarang', $report->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-slate-700">
                                Catatan (akan disimpan di laporan)
                            </label>
                            <textarea
                                name="admin_notes"
                                rows="2"
                                class="w-full rounded-2xl border border-amber-200 px-3 py-2 text-xs sm:text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-400 bg-amber-50/60"
                                placeholder="Contoh: barang disembunyikan karena tidak layak, menunggu klarifikasi dari donatur."></textarea>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center w-full gap-1.5 px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-semibold shadow-md">
                            <i data-lucide="eye-off" class="w-4 h-4"></i>
                            Sembunyikan Barang dari Etalase
                        </button>
                    </form>

                    <p class="text-[11px] text-amber-600 mt-1">
                        Barang yang disembunyikan tidak akan tampil di halaman etalase, tapi masih tersimpan di database.
                    </p>
                </section>
            @endif
        </div>
    </div>
</div>
@endsection
