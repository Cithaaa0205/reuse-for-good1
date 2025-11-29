@extends('layouts.app')

@section('title', 'Edit Profil')
@section('showBackButton', true)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header kecil --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-md">
        <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_left,_#ffffff,_transparent_55%)]"></div>
        <div class="relative px-6 sm:px-8 py-5 sm:py-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] tracking-[0.16em] font-semibold uppercase text-blue-100">
                    Pengaturan Profil
                </p>
                <h1 class="text-2xl font-extrabold leading-tight">
                    Edit Profil
                </h1>
                <p class="text-xs sm:text-sm text-blue-50/90 mt-1">
                    Perbarui informasi akunmu agar penerima dan donatur lain lebih mudah mengenalmu ✨
                </p>
            </div>
        </div>
    </section>

    {{-- Alert error --}}
    @if ($errors->any())
        <div class="flex items-start gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5"></i>
            <div>
                <p class="font-semibold text-[13px] mb-1">Oops! Ada beberapa hal yang perlu dicek:</p>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- BAGIAN: INFO UTAMA --}}
        <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-5">

            {{-- Foto profil --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <div class="relative">
                    <img
                        id="foto-preview"
                        src="{{ $user->foto_profil
                                ? asset('uploads/avatars/' . $user->foto_profil)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_lengkap) . '&background=E0F7FA&color=0284C7&size=160' }}"
                        alt="Avatar"
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-blue-100 object-cover shadow-md bg-white"
                    >
                    <span class="absolute -bottom-2 right-0 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white shadow-md text-[11px]">
                        <i data-lucide="camera" class="w-3 h-3"></i>
                    </span>
                </div>

                <div class="flex-1 space-y-2 text-center sm:text-left">
                    <p class="text-sm font-semibold text-slate-900">
                        {{ $user->nama_lengkap }}
                    </p>
                    <p class="text-xs text-slate-500">
                        @<span>{{ $user->username }}</span> • Bergabung {{ $user->created_at->isoFormat('MMMM YYYY') }}
                    </p>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-2">
                        <label for="foto_profil"
                               class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                            Ubah Foto
                        </label>
                        <p class="text-[11px] text-slate-400">
                            Maks. 2MB, format JPG/PNG
                        </p>
                    </div>
                    <input type="file" id="foto_profil" name="foto_profil" class="hidden" accept="image/*">
                </div>
            </div>

            @php
                $provOptions      = ['DI Yogyakarta', 'Jawa Tengah', 'Jawa Barat', 'Jawa Timur'];
                $selectedProvinsi = old('provinsi', $user->provinsi);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                {{-- Nama --}}
                <div class="space-y-1.5">
                    <label for="nama_lengkap" class="text-xs font-medium text-slate-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required
                    >
                </div>

                {{-- Username --}}
                <div class="space-y-1.5">
                    <label for="username" class="text-xs font-medium text-slate-700">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required
                    >
                </div>

                {{-- Nomor Telepon --}}
                <div class="space-y-1.5">
                    <label for="nomor_telepon" class="text-xs font-medium text-slate-700">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="tel"
                        id="nomor_telepon"
                        name="nomor_telepon"
                        value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required
                    >
                </div>

                {{-- Provinsi (sekarang opsional di edit) --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-slate-700">
                        Provinsi <span class="text-[10px] text-slate-400">(opsional di sini)</span>
                    </label>
                    <select id="provinsi" name="provinsi"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                   bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2
                                   focus:ring-blue-200 focus:border-blue-400 transition">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provOptions as $prov)
                            <option value="{{ $prov }}" {{ $selectedProvinsi === $prov ? 'selected' : '' }}>
                                {{ $prov }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kabupaten (opsional di edit) --}}
                <div class="space-y-1.5 sm:col-span-2">
                    <label class="text-xs font-medium text-slate-700">
                        Kabupaten/Kota <span class="text-[10px] text-slate-400">(opsional di sini)</span>
                    </label>
                    <select id="kabupaten" name="kabupaten"
                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                                   bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2
                                   focus:ring-blue-200 focus:border-blue-400 transition">
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="deskripsi" class="text-xs font-medium text-slate-700">
                        Deskripsi / Bio
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2
                               focus:ring-blue-200 focus:border-blue-400 transition"
                        placeholder="Suka berbagi barang yang masih layak pakai..."
                    >{{ old('deskripsi', $user->deskripsi) }}</textarea>
                </div>
            </div>
        </section>

        {{-- BAGIAN: PASSWORD --}}
        <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-semibold text-slate-900">Keamanan Akun</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">
                        Kosongkan jika kamu tidak ingin mengubah password.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 text-[11px] text-slate-600 border border-slate-200">
                    <i data-lucide="shield" class="w-3 h-3"></i>
                    Disarankan ganti secara berkala
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-medium text-slate-700">
                        Password Baru
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        placeholder="Minimal 8 karakter"
                    >
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-xs font-medium text-slate-700">
                        Konfirmasi Password Baru
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-slate-800
                               bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        placeholder="Ketik ulang password baru"
                    >
                </div>
            </div>
        </section>

        {{-- BUTTON --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 pt-1">
            <a href="{{ route('profile.show', $user->username) }}"
               class="w-full sm:w-1/3 inline-flex items-center justify-center px-4 py-2.5 rounded-2xl text-sm font-semibold
                      bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 transition">
                Batal
            </a>

            <button
                type="submit"
                class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-semibold
                       bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg active:scale-[0.99] transition"
            >
                <i data-lucide="save" class="w-4 h-4"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Preview foto profil
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

    // Data kabupaten per provinsi
    const kabupatenData = {
        "DI Yogyakarta": ["Yogyakarta", "Sleman", "Bantul", "Kulon Progo", "Gunungkidul"],
        "Jawa Tengah": ["Semarang", "Surakarta", "Magelang", "Tegal", "Purwokerto"],
        "Jawa Barat": ["Bandung", "Bogor", "Bekasi", "Tasikmalaya", "Cirebon"],
        "Jawa Timur": ["Surabaya", "Malang", "Kediri", "Madiun", "Banyuwangi"],
    };

    const provinsiSelect  = document.getElementById('provinsi');
    const kabupatenSelect = document.getElementById('kabupaten');

    const initialProvinsi  = @json(old('provinsi', $user->provinsi));
    const initialKabupaten = @json(old('kabupaten', $user->kabupaten));

    function populateKabupaten(provinsi, selectedKabupaten) {
        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';

        const list = kabupatenData[provinsi] || [];
        list.forEach(kab => {
            const selected = (kab === selectedKabupaten) ? 'selected' : '';
            kabupatenSelect.insertAdjacentHTML(
                'beforeend',
                `<option value="${kab}" ${selected}>${kab}</option>`
            );
        });
    }

    // Saat halaman pertama kali dibuka, isi kabupaten sesuai data user
    if (initialProvinsi) {
        populateKabupaten(initialProvinsi, initialKabupaten);
    }

    // Saat provinsi diganti
    provinsiSelect.addEventListener('change', function () {
        populateKabupaten(this.value, null);
    });
});
</script>
@endpush
