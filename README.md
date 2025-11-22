@extends('layouts.app')

@section('title', 'Tentang Reuse For Good')

{{-- Set true agar tombol back muncul di header --}}
@section('showBackButton', true)

@section('content')
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Header Hitam -->
    <div class="bg-gray-900 text-white p-10 md:p-16 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-3">Tentang ReuseForGood</h1>
        <p class="text-lg md:text-xl text-gray-300">Mengubah barang bekas menjadi harapan baru bagi sesama</p>
    </div>


    
    <!-- Konten Utama -->
    <div class="p-6 md:p-12">
        <!-- Grid 3 Kolom -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Kolom 1: Logo & Deskripsi -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-center flex flex-col items-center">
                <img src="{{ asset('foto/Logo.png') }}"  alt="Logo RFG" class="w-32 h-32 rounded-full mb-4 border-4 border-white shadow-md">
                <h3 class="text-xl font-semibold mb-2">REUSEFORGOOD</h3>
                <p class="text-gray-700 text-sm">
                    Adalah platform digital yang menghubungkan pendonasi dengan penerima barang bekas layak pakai. Kami percaya setiap barang bekas memiliki nilai guna yang bisa terus dimanfaatkan.
                </p>
            </div>
            
            <!-- Kolom 2: Sejarah -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-center flex flex-col items-center">
                <img src="{{ asset('foto/Starpride2.png') }}"  alt="Tim ReuseForGood" class="w-full h-40 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-2">Dibentuk pada: 19 Februari 2025</h3>
                <p class="text-gray-700 text-sm">
                    <strong>Latar Belakang:</strong> Bagian dari tugas Rekayasa Perangkat Lunak dan dikembangkan lebih lanjut pada Proyek Informatika sebagai bentuk kepedulian terhadap barang yang terbuang sia-sia.
                </p>
            </div>

            <!-- Kolom 3: Tim Kami -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 text-center flex flex-col items-center">
                <img src="{{ asset('foto/Starpride1.png') }}" alt="Tim ReuseForGood" class="w-full h-40 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-semibold mb-2">TIM KAMI</h3>
                <p class="text-gray-700 text-sm">
                    REUSEFORGOOD dikembangkan oleh Mahasiswa yang peduli terhadap pemanfaatan barang bekas dan pengembangan teknologi untuk kebaikan sosial.
                </p>
            </div>
        </div>

        <!-- Bagian Contact Us -->
        <div class="text-center mb-10">
            <img src="{{ asset('foto/Logo.png') }}" alt="Logo RFG" class="w-36 h-36 rounded-full mb-4 border-4 border-white shadow-lg mx-auto">
            <h2 class="text-3xl font-bold text-blue-600 mb-2">Contact Us</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Gunakan informasi di bawah ini untuk menghubungi kami secara langsung atau ajukan pernyataan melalui form kontak
            </p>
        </div>

        <!-- Grid 4 Kolom Kontak -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 p-6 rounded-xl text-center shadow-sm border border-blue-100">
                <i data-lucide="globe" class="w-12 h-12 text-blue-600 mx-auto mb-3"></i>
                <h4 class="text-lg font-semibold mb-1">Website</h4>
                <a href="#" class="text-blue-600 hover:underline">www.reuseforgood.com</a>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl text-center shadow-sm border border-blue-100">
                <i data-lucide="mail" class="w-12 h-12 text-blue-600 mx-auto mb-3"></i>
                <h4 class="text-lg font-semibold mb-1">Email</h4>
                <a href="mailto:hello@reuseforgood.com" class="text-blue-600 hover:underline">hello@reuseforgood.com</a>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl text-center shadow-sm border border-blue-100">
                <i data-lucide="phone" class="w-12 h-12 text-blue-600 mx-auto mb-3"></i>
                <h4 class="text-lg font-semibold mb-1">Telepon</h4>
                <p class="text-gray-700">123-456-7890</p>
            </div>
            <div class="bg-blue-50 p-6 rounded-xl text-center shadow-sm border border-blue-100">
                <i data-lucide="map-pin" class="w-12 h-12 text-blue-600 mx-auto mb-3"></i>
                <h4 class="text-lg font-semibold mb-1">Alamat</h4>
                <p class="text-gray-700">123 Paingan St,Yogyakarta</p>
            </div>
        </div>
    </div>
</div>
@endsection
