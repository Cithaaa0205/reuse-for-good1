@extends('layouts.app')

@section('title', 'Kelola Pengajuan')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Pengajuan</h1>
            <p class="text-sm text-gray-500">
                Daftar permintaan penerimaan barang untuk donasi kamu.
            </p>
        </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($pengajuan->isEmpty())
        <div class="bg-white rounded-2xl shadow p-6 text-center text-gray-500">
            Belum ada pengajuan untuk barang donasi kamu.
        </div>
    @else
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Barang</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Penerima</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Diajukan</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pengajuan as $req)
                        @php
                            $barang = $req->barangDonasi;
                            $penerima = $req->penerima;
                            $isPending = $req->status === 'Diajukan';

                            $badgeColor = [
                                'Diajukan' => 'bg-yellow-100 text-yellow-800',
                                'Disetujui' => 'bg-green-100 text-green-800',
                                'Ditolak' => 'bg-red-100 text-red-800',
                            ][$req->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($barang && $barang->foto_barang_utama)
                                        <img src="{{ asset('uploads/barang/' . $barang->foto_barang_utama) }}"
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                            No Image
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $barang->nama_barang ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID Pengajuan #{{ $req->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $penerima->nama_lengkap ?? $penerima->username ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $penerima->email ?? '' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                    {{ $req->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $req->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Tombol Tolak --}}
                                    <form action="{{ route('request.reject', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 rounded-lg border text-xs font-semibold
                                                       {{ $isPending ? 'border-red-500 text-red-600 hover:bg-red-50' : 'border-gray-200 text-gray-400 cursor-not-allowed' }}"
                                                {{ $isPending ? '' : 'disabled' }}>
                                            Tolak
                                        </button>
                                    </form>

                                    {{-- Tombol Setujui --}}
                                    <form action="{{ route('request.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1 rounded-lg text-xs font-semibold text-white
                                                       {{ $isPending ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-300 cursor-not-allowed' }}"
                                                {{ $isPending ? '' : 'disabled' }}>
                                            Setujui
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
