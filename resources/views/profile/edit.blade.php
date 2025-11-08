@extends('layouts.app')

@section('title', 'Edit Profil')

@section('showBackButton', true)

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-lg p-6 md:p-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Profil</h1>

    @if ($errors->any())
        <!-- ... (Error handling tidak berubah) ... -->
    @endif

    <!-- PENTING: Tambahkan enctype untuk upload file -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            
            <!-- TAMBAHAN: Upload Foto Profil -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <img id="foto-preview" 
                         src="{{ $user->foto_profil ? asset('uploads/avatars/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_lengkap) . '&background=E0F7FA&color=0284C7&size=96' }}"
                         alt="Avatar" class="w-24 h-24 rounded-full border-4 border-blue-100 object-cover">
                    <label for="foto_profil" class="cursor-pointer px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">
                        Ubah Foto
                    </label>
                    <input type="file" id="foto_profil" name="foto_profil" class="hidden" accept="image/*">
                </div>
            </div>

            <div>
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            
            <div>
                <label for="nomor_telepon" class="block mb-2 text-sm font-medium text-gray-700">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- TAMBAHAN: Deskripsi -->
            <div>
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-700">Deskripsi / Bio</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Suka berbagi barang yang masih layak pakai...">{{ old('deskripsi', $user->deskripsi) }}</textarea>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <!-- ... (Form ganti password tidak berubah) ... -->
            </div>

            <div class="pt-2">
                 <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 text-lg">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk preview foto profil
    document.addEventListener('DOMContentLoaded', () => {
        const inputFoto = document.getElementById('foto_profil');
        const previewImg = document.getElementById('foto-preview');

        if (inputFoto && previewImg) {
            inputFoto.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush