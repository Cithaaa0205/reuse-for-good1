@extends('layouts.app')

@section('title', 'Pilih Lokasi Anda')
@section('showBackButton', false)

@section('content')
<div class="max-w-2xl mx-auto mt-6">

    {{-- Wrapper dengan gradasi & bayangan biru --}}
    <div class="bg-gradient-to-br from-blue-50 via-sky-50 to-cyan-50 rounded-[2rem] p-[1.5px] shadow-[0_15px_40px_rgba(37,99,235,0.18)]">
        {{-- Card utama putih di dalam gradasi --}}
        <div class="bg-white/95 rounded-[1.9rem] px-8 py-8 sm:px-10 sm:py-9">

            {{-- Header kecil di atas judul --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[11px] font-semibold text-blue-700 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                Atur lokasi untuk rekomendasi yang lebih tepat
            </div>

            {{-- Heading --}}
            <div class="text-center mb-7">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Pilih Lokasi Utama
                </h1>
                <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                    Kami akan menampilkan rekomendasi barang donasi yang paling dekat dengan lokasimu.
                    Kamu bisa mengubah lokasi ini kapan saja dari halaman profil.
                </p>
            </div>

            {{-- Error box --}}
            @if($errors->any())
                <div class="bg-red-50/90 border border-red-200 text-red-600 text-xs rounded-2xl px-4 py-3 mb-5 shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('lokasi.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Provinsi --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-1.5">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="provinsi" name="provinsi"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500
                                   hover:border-blue-300 transition-all shadow-sm">
                            <option value="">Pilih Provinsi</option>
                            <option value="DI Yogyakarta">DI Yogyakarta</option>
                            <option value="Jawa Tengah">Jawa Tengah</option>
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Jawa Timur">Jawa Timur</option>
                        </select>
                    </div>
                </div>

                {{-- Kabupaten --}}
                <div>
                    <label class="text-sm font-semibold text-slate-700 block mb-1.5">
                        Kabupaten/Kota <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="kabupaten" name="kabupaten"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-300 bg-slate-50 text-slate-800 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500
                                   hover:border-blue-300 transition-all shadow-sm">
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol --}}
                <button type="submit"
                    class="w-full mt-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl
                           bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold text-sm
                           shadow-lg shadow-blue-600/25 hover:shadow-xl hover:shadow-blue-600/35
                           active:scale-[0.98] transition-all duration-200">
                    Simpan Lokasi &amp; Lanjutkan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const kabupatenData = {
        "DI Yogyakarta": ["Yogyakarta", "Sleman", "Bantul", "Kulon Progo", "Gunungkidul"],
        "Jawa Tengah": ["Semarang", "Surakarta", "Magelang", "Tegal", "Purwokerto"],
        "Jawa Barat": ["Bandung", "Bogor", "Bekasi", "Tasikmalaya", "Cirebon"],
        "Jawa Timur": ["Surabaya", "Malang", "Kediri", "Madiun", "Banyuwangi"],
    };

    const provinsiSelect  = document.getElementById("provinsi");
    const kabupatenSelect = document.getElementById("kabupaten");

    provinsiSelect.addEventListener("change", function () {
        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        const list = kabupatenData[this.value] || [];
        list.forEach(k => kabupatenSelect.insertAdjacentHTML(
            "beforeend",
            `<option value="${k}">${k}</option>`
        ));
    });
});
</script>
@endpush
