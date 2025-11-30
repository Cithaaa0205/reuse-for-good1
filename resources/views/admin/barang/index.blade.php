@extends('layouts.app')

@section('title', 'Manajemen Barang Donasi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                Panel Admin • Manajemen Barang
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">
                Manajemen Barang Donasi
            </h1>
            <p class="text-sm text-slate-500 mt-1 max-w-xl">
                Atur daftar barang donasi di Reuse For Good. Admin bisa menghapus atau menyembunyikan barang dari etalase.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="px-3 py-1.5 rounded-full text-[11px] font-medium bg-slate-900 text-sky-100 shadow-sm">
                <span class="inline-flex items-center gap-1">
                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                    Akses Khusus Admin
                </span>
            </div>
        </div>
    </div>

    {{-- INFO CHIP --}}
    <div class="flex flex-wrap gap-2 text-[11px] sm:text-xs">
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-900 text-sky-100 shadow-sm">
            <i data-lucide="package" class="w-3 h-3"></i>
            <span><strong>{{ $totalBarang ?? $barang->count() }}</strong> total barang</span>
        </span>
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
            <i data-lucide="check-circle-2" class="w-3 h-3"></i>
            <span>{{ $totalTersedia ?? 0 }} tersedia</span>
        </span>
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
            <i data-lucide="shopping-bag" class="w-3 h-3"></i>
            <span>{{ $totalDipesan ?? 0 }} dipesan</span>
        </span>
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-100">
            <i data-lucide="eye-off" class="w-3 h-3"></i>
            <span>{{ $totalHidden ?? 0 }} disembunyikan</span>
        </span>
    </div>

    {{-- SEARCH --}}
    <div class="mt-2">
        <div class="relative max-w-xl">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input
                id="barang-search"
                type="text"
                placeholder="Cari barang, kategori, lokasi, atau donatur..."
                class="w-full pl-9 pr-3 py-2.5 rounded-2xl border border-slate-200 text-xs sm:text-sm text-slate-700
                       bg-white/90 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition shadow-sm"
            >
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

    {{-- TABLE WRAPPER --}}
    <div class="rounded-3xl bg-gradient-to-br from-slate-900/5 via-blue-100/10 to-sky-100/20 p-[1.5px] shadow-xl shadow-slate-200/60">
        <div class="overflow-hidden bg-white/95 backdrop-blur rounded-[1.4rem] border border-slate-100">
            <div class="px-4 sm:px-6 py-3 border-b border-slate-100 flex items-center justify-between gap-2">
                <h2 class="text-sm font-semibold text-slate-900">
                    Daftar Barang Donasi
                </h2>
                <span class="text-[11px] text-slate-400">
                    Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="barang-table" class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-600">
                            <th class="px-4 py-3 text-left font-semibold">ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Barang</th>
                            <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-4 py-3 text-left font-semibold">Donatur</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barang as $item)
                            <tr class="hover:bg-slate-50/80 transition"
                                data-row="{{ strtolower(
                                    $item->nama_barang . ' ' .
                                    ($item->kategori->nama_kategori ?? '') . ' ' .
                                    ($item->donatur->nama_lengkap ?? '') . ' ' .
                                    ($item->provinsi ?? '') . ' ' .
                                    ($item->kabupaten ?? '')
                                ) }}">
                                <td class="px-4 py-3 text-slate-500">{{ $item->id }}</td>
                                <td class="px-4 py-3 text-slate-900 font-medium">
                                    {{ $item->nama_barang }}
                                    <div class="text-[11px] text-slate-400">
                                        {{ $item->provinsi }}, {{ $item->kabupaten }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $item->donatur->nama_lengkap ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        {{-- Status barang --}}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                            @if($item->status === 'Tersedia')
                                                bg-emerald-50 text-emerald-700 border border-emerald-100
                                            @elseif($item->status === 'Dipesan')
                                                bg-amber-50 text-amber-700 border border-amber-100
                                            @else
                                                bg-slate-50 text-slate-600 border border-slate-100
                                            @endif">
                                            {{ $item->status ?? '-' }}
                                        </span>

                                        {{-- Status visibilitas --}}
                                        @if($item->is_hidden)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                                bg-rose-50 text-rose-700 border border-rose-100">
                                                <i data-lucide="eye-off" class="w-3 h-3 mr-1"></i>
                                                Tersembunyi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                                bg-blue-50 text-blue-700 border border-blue-100">
                                                <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                                Ditampilkan
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex flex-wrap items-center gap-2 justify-center">

                                        {{-- Toggle hide / unhide --}}
                                        @if(!$item->is_hidden)
                                            <form action="{{ route('admin.barang.hide', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Sembunyikan barang ini dari etalase user?');">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                                               bg-amber-400 hover:bg-amber-500 text-white shadow-sm transition">
                                                    <i data-lucide="eye-off" class="w-3 h-3 mr-1"></i>
                                                    Sembunyikan
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.barang.unhide', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Tampilkan kembali barang ini di etalase user?');">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                                               bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm transition">
                                                    <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                                                    Tampilkan
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Hapus permanen --}}
                                        <form action="{{ route('admin.barang.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus barang ini secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-[11px] font-semibold rounded-full 
                                                           bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                                                <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    Belum ada barang donasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('barang-search');
        const table       = document.getElementById('barang-table');

        if (searchInput && table) {
            const rows = table.querySelectorAll('tbody tr[data-row]');
            searchInput.addEventListener('input', () => {
                const q = searchInput.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.getAttribute('data-row') || '';
                    row.style.display = text.includes(q) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
@endsection
