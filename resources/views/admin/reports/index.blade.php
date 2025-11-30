@extends('layouts.app')

@section('title', 'Laporan & Moderasi')

@section('content')
@php
    use App\Models\Report;

    $currentStatus = $status ?? null;

    $totalReports = Report::count();
    $totalBaru    = Report::where('status', Report::STATUS_BARU)->count();
    $totalProses  = Report::where('status', Report::STATUS_DIPROSES)->count();
    $totalSelesai = Report::where('status', Report::STATUS_SELESAI)->count();

    function badgeStatusClass($status) {
        return match ($status) {
            Report::STATUS_BARU => 'bg-rose-50 text-rose-700 border border-rose-100',
            Report::STATUS_DIPROSES => 'bg-amber-50 text-amber-700 border border-amber-100',
            Report::STATUS_SELESAI => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            default => 'bg-slate-50 text-slate-600 border border-slate-100',
        };
    }

    function badgeStatusDotClass($status) {
        return match ($status) {
            Report::STATUS_BARU => 'bg-rose-500',
            Report::STATUS_DIPROSES => 'bg-amber-500',
            Report::STATUS_SELESAI => 'bg-emerald-500',
            default => 'bg-slate-400',
        };
    }

    function typeLabel($type) {
        return match ($type) {
            Report::TYPE_BARANG => 'Barang',
            Report::TYPE_USER   => 'User',
            Report::TYPE_PESAN  => 'Pesan',
            default             => ucfirst($type ?? '-'),
        };
    }
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                Panel Admin • Laporan & Moderasi
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
                Laporan & Keamanan Platform
            </h1>
            <p class="text-sm text-slate-500 mt-1 max-w-xl">
                Pantau laporan dari pengguna, ambil tindakan terhadap barang, user, atau pesan yang bermasalah.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2 justify-start sm:justify-end">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 text-sky-100 text-[11px] font-medium shadow-sm">
                <i data-lucide="shield-check" class="w-3 h-3"></i>
                <span>Area moderasi sensitif</span>
            </span>
        </div>
    </div>

    {{-- RINGKASAN ANGKA --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white/95 rounded-2xl border border-slate-100 shadow-sm px-4 py-3">
            <p class="text-[11px] text-slate-400 mb-1 flex items-center gap-1">
                <i data-lucide="file-warning" class="w-3 h-3"></i>
                Total laporan
            </p>
            <p class="text-2xl font-bold text-slate-900">{{ $totalReports }}</p>
        </div>

        <div class="bg-rose-50/90 rounded-2xl border border-rose-100 shadow-sm px-4 py-3">
            <p class="text-[11px] text-rose-600 mb-1 flex items-center gap-1">
                <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                Baru masuk
            </p>
            <p class="text-2xl font-bold text-rose-700">{{ $totalBaru }}</p>
        </div>

        <div class="bg-amber-50/90 rounded-2xl border border-amber-100 shadow-sm px-4 py-3">
            <p class="text-[11px] text-amber-700 mb-1 flex items-center gap-1">
                <i data-lucide="loader-2" class="w-3 h-3"></i>
                Sedang diproses
            </p>
            <p class="text-2xl font-bold text-amber-700">{{ $totalProses }}</p>
        </div>

        <div class="bg-emerald-50/90 rounded-2xl border border-emerald-100 shadow-sm px-4 py-3">
            <p class="text-[11px] text-emerald-700 mb-1 flex items-center gap-1">
                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                Selesai
            </p>
            <p class="text-2xl font-bold text-emerald-700">{{ $totalSelesai }}</p>
        </div>
    </div>

    {{-- FILTER STATUS + SEARCH (client-side) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="inline-flex flex-wrap gap-2 bg-white/90 border border-slate-200 rounded-2xl px-2 py-1 shadow-sm">
            {{-- ALL --}}
            <a href="{{ route('admin.reports.index') }}"
               class="px-3 py-1.5 rounded-full text-[11px] font-semibold flex items-center gap-1.5
                      {{ !$currentStatus ? 'bg-slate-900 text-sky-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                <i data-lucide="infinity" class="w-3 h-3"></i>
                Semua
            </a>

            {{-- BARU --}}
            <a href="{{ route('admin.reports.index', ['status' => Report::STATUS_BARU]) }}"
               class="px-3 py-1.5 rounded-full text-[11px] font-semibold flex items-center gap-1.5
                      {{ $currentStatus === Report::STATUS_BARU ? 'bg-rose-500 text-white shadow-sm' : 'text-rose-600 hover:bg-rose-50' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                Baru ({{ $totalBaru }})
            </a>

            {{-- DIPROSES --}}
            <a href="{{ route('admin.reports.index', ['status' => Report::STATUS_DIPROSES]) }}"
               class="px-3 py-1.5 rounded-full text-[11px] font-semibold flex items-center gap-1.5
                      {{ $currentStatus === Report::STATUS_DIPROSES ? 'bg-amber-500 text-white shadow-sm' : 'text-amber-700 hover:bg-amber-50' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Diproses ({{ $totalProses }})
            </a>

            {{-- SELESAI --}}
            <a href="{{ route('admin.reports.index', ['status' => Report::STATUS_SELESAI]) }}"
               class="px-3 py-1.5 rounded-full text-[11px] font-semibold flex items-center gap-1.5
                      {{ $currentStatus === Report::STATUS_SELESAI ? 'bg-emerald-500 text-white shadow-sm' : 'text-emerald-700 hover:bg-emerald-50' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Selesai ({{ $totalSelesai }})
            </a>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input
                    id="report-search"
                    type="text"
                    placeholder="Cari alasan, pelapor, atau target..."
                    class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-700
                           bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition shadow-sm"
                >
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

    {{-- TABEL LAPORAN --}}
    <div class="rounded-3xl bg-gradient-to-br from-slate-900/5 via-blue-100/10 to-sky-100/20 p-[1.5px] shadow-xl shadow-slate-200/60">
        <div class="overflow-hidden bg-white/95 backdrop-blur rounded-[1.4rem] border border-slate-100">
            <div class="px-4 sm:px-6 py-3 border-b border-slate-100 flex items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-900">
                    Daftar Laporan
                </h2>
                <span class="text-[11px] text-slate-400">
                    Menampilkan {{ $reports->firstItem() ?? 0 }}–{{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} laporan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="report-table" class="min-w-full text-xs sm:text-sm">
                    <thead>
                    <tr class="bg-slate-50/80 text-slate-600">
                        <th class="px-4 py-3 text-left font-semibold">ID</th>
                        <th class="px-4 py-3 text-left font-semibold">Tipe</th>
                        <th class="px-4 py-3 text-left font-semibold">Target</th>
                        <th class="px-4 py-3 text-left font-semibold">Pelapor</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">Dibuat</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        @php
                            $target = $report->target_model;

                            // Label target
                            $targetLabel = '-';
                            if ($report->reported_type === Report::TYPE_BARANG && $target) {
                                $targetLabel = $target->nama_barang ?? ('Barang #' . $target->id);
                            } elseif ($report->reported_type === Report::TYPE_USER && $target) {
                                $targetLabel = $target->nama_lengkap ?? ('User #' . $target->id);
                            } elseif ($report->reported_type === Report::TYPE_PESAN && $target) {
                                $targetLabel = \Illuminate\Support\Str::limit($target->message ?? '', 60, '…');
                            }

                            $rowSearch = strtolower(
                                $report->reason . ' ' .
                                ($report->reporter->nama_lengkap ?? '') . ' ' .
                                ($report->reporter->email ?? '') . ' ' .
                                $targetLabel
                            );
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition"
                            data-row="{{ $rowSearch }}">
                            <td class="px-4 py-3 text-slate-500">#{{ $report->id }}</td>

                            {{-- Tipe --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-50 text-[11px] text-slate-700 border border-slate-100">
                                    @if($report->reported_type === Report::TYPE_BARANG)
                                        <i data-lucide="package" class="w-3 h-3 text-blue-500"></i>
                                    @elseif($report->reported_type === Report::TYPE_USER)
                                        <i data-lucide="user" class="w-3 h-3 text-emerald-500"></i>
                                    @else
                                        <i data-lucide="message-square" class="w-3 h-3 text-amber-500"></i>
                                    @endif
                                    <span>{{ typeLabel($report->reported_type) }}</span>
                                </span>
                            </td>

                            {{-- Target --}}
                            <td class="px-4 py-3 max-w-sm">
                                <p class="text-slate-900 text-xs sm:text-sm truncate">{{ $targetLabel }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">
                                    {{ $report->reason }}
                                </p>
                            </td>

                            {{-- Pelapor --}}
                            <td class="px-4 py-3">
                                <div class="space-y-0.5">
                                    <p class="text-xs sm:text-sm font-semibold text-slate-900">
                                        {{ $report->reporter->nama_lengkap ?? 'User #' . $report->reporter_id }}
                                    </p>
                                    <p class="text-[11px] text-slate-400">
                                        {{ $report->reporter->email ?? '-' }}
                                    </p>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ badgeStatusClass($report->status) }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ badgeStatusDotClass($report->status) }}"></span>
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>

                            {{-- Dibuat --}}
                            <td class="px-4 py-3 text-slate-500 hidden sm:table-cell">
                                {{ $report->created_at->format('d M Y H:i') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.reports.show', $report->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                          bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                                    <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500 text-sm">
                                Belum ada laporan yang masuk.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 sm:px-6 py-3 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Filter client-side simple
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('report-search');
        const rows  = document.querySelectorAll('#report-table tbody tr');

        if (!input) return;

        input.addEventListener('input', () => {
            const q = input.value.toLowerCase();

            rows.forEach(row => {
                const text = row.getAttribute('data-row') || '';
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });
</script>
@endpush
@endsection
