@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Pengguna</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-2 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-4">
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
                <option value="donatur" {{ old('role', $user->role) === 'donatur' ? 'selected' : '' }}>Donatur</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:underline">← Kembali</a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
