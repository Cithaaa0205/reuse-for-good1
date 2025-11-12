@extends('layouts.app')

{{-- Tampilkan tombol back, tapi arahkan ke daftar chat --}}
@section('showBackButton', true)
@section('backButtonUrl', route('chat.index'))

@section('content')
<div class="max-w-lg mx-auto flex flex-col h-[calc(100vh-120px)]"> <!-- Kontainer utama chat -->
    
    <!-- Header Chat (Nama & Avatar) -->
    <div class="flex-shrink-0 bg-white shadow rounded-t-xl p-4 flex items-center gap-3">
        @if($otherUser->foto_profil)
            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('uploads/avatars/' . $otherUser->foto_profil) }}" alt="Foto Profil">
        @else
            <div class="h-10 w-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-lg">
                {{ strtoupper(substr($otherUser->nama_lengkap, 0, 2)) }}
            </div>
        @endif
        <div>
            <h2 class="font-bold text-gray-900">{{ $otherUser->nama_lengkap }}</h2>
            <p class="text-xs text-gray-500">@<span>{{ $otherUser->username }}</span></p>
        </div>
        {{-- TODO: Tambahkan info barang yang sedang dibahas --}}
        {{-- <div class="ml-auto text-right">
            <img src="https://placehold.co/40x40" class="w-10 h-10 rounded object-cover ml-auto">
            <span class="text-xs text-gray-500">Rice Cooker</span>
        </div> --}}
    </div>

    <!-- Area Pesan (Bisa di-scroll) -->
    <div id="message-container" class="flex-1 overflow-y-auto bg-gray-100 p-4 space-y-4">
        
        @forelse ($messages as $message)
            @if ($message->sender_id == Auth::id())
                <!-- Pesan Saya (Kanan, Hijau) -->
                <div class="flex justify-end">
                    <div class="bg-green-600 text-white p-3 rounded-lg rounded-br-none max-w-xs md:max-w-md shadow">
                        <p>{{ $message->message }}</p>
                        <span class="text-xs text-green-200 block text-right mt-1">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @else
                <!-- Pesan Orang Lain (Kiri, Putih) -->
                <div class="flex justify-start">
                    <div class="bg-white text-gray-800 p-3 rounded-lg rounded-bl-none max-w-xs md:max-w-md shadow">
                        <p>{{ $message->message }}</p>
                        <span class="text-xs text-gray-500 block text-right mt-1">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                </div>
            @endif
        @empty
             <div class="text-center text-gray-500 p-10">
                <i data-lucide="message-square" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <p>Mulai percakapan</p>
            </div>
        @endforelse

    </div>

    <!-- Form Input Pesan (Sticky di bawah) -->
    <div class="flex-shrink-0 bg-white p-4 rounded-b-xl shadow-inner">
        <form action="{{ route('chat.store', $otherUser->id) }}" method="POST">
            @csrf
            <div class="flex items-center gap-2">
                <input type="text" name="message" 
                       class="flex-1 border border-gray-300 rounded-full py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="Ketik pesan..." autocomplete="off">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full flex-shrink-0 transition-colors">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Script untuk auto-scroll ke pesan terakhir
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('message-container');
        if(container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endsection