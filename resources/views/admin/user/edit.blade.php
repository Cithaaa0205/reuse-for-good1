@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-xl mx-auto py-8 space-y-6">
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 mb-1">
            Panel Admin • Edit Pengguna
        </p>
        <h1 class="text-2xl font-bold text-slate-900">Edit Pengguna</h1>
        <p class="text-sm text-slate-500 mt-1">
            Perbarui informasi dasar dan role pengguna. Status keamanan akun diatur dari halaman daftar pengguna.
        </p>
    </div>

    {{-- Kartu status akun --}}
    @php
        $status = $user->status ?? 'aktif';
        $statusLabel = ucfirst($status);

        $statusClasses = match ($status) {
            'aktif'     => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
            'suspended' => 'bg-amber-50 text-amber-700 border border-amber-100',
            'banned'    => 'bg-rose-50 text-rose-700 border border-rose-100',
            default     => 'bg-slate-50 text-slate-600 border border-slate-200',
        };

        $statusDot = match ($status) {
            'aktif'     => 'bg-emerald-500',
            'suspended' => 'bg-amber-500',
            'banned'    => 'bg-rose-500',
            default     => 'bg-slate-400',
        };
    @endphp

    <div class="rounded-2xl px-4 py-3 bg-slate-900 text-slate-50 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <div class="h-9 w-9 rounded-xl bg-slate-800 flex items-center justify-center">
                <i data-lucide="shield" class="w-4 h-4 text-sky-300"></i>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-slate-300">Status & Keamanan Akun</p>
                <p class="text-sm font-semibold">
                    {{ $user->nama_lengkap }}
                </p>
            </div>
        </div>
        <div class="mt-2 sm:mt-0 text-right space-y-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClasses }}">
                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $statusDot }}"></span>
                {{ $statusLabel }}
            </span>
            @if($user->status_changed_at)
                <p class="text-[10px] text-slate-300">
                    Diubah: {{ $user->status_changed_at->format('d M Y H:i') }}
                </p>
            @endif
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded-xl">
            <ul class="list-disc list-inside text-xs sm:text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
            <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="user" {{ in_array(old('role', $user->role), ['user', 'donatur']) ? 'selected' : '' }}>
                    User Biasa
                </option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
            </select>
        </div>

        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali ke daftar pengguna
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                <i data-lucide="check" class="w-4 h-4 mr-1"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
