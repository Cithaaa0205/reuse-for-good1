@extends('layouts.app')

@section('title', 'Pesan')

{{-- Kita gunakan tombol back bawaan dari layout --}}
@section('showBackButton', true)

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Pesan</h1>

    <div class="space-y-4">
        @forelse ($conversations as $convo)
            <a href="{{ route('chat.show', $convo['user']->id) }}" class="block w-full bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    @if($convo['user']->foto_profil)
                        <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('uploads/avatars/' . $convo['user']->foto_profil) }}" alt="Foto Profil">
                    @else
                        <div class="h-12 w-12 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($convo['user']->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    
                    <!-- Info Chat -->
                    <div class="flex-1 overflow-hidden">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 truncate">{{ $convo['user']->nama_lengkap }}</h3>
                            <span class="text-xs text-gray-500 flex-shrink-0 ml-2">{{ $convo['lastMessage']->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">
                            @if($convo['lastMessage']->sender_id == Auth::id())
                                <span class="font-semibold">Anda:</span> 
                            @endif
                            {{ $convo['lastMessage']->message }}
                        </p>
                        {{-- TODO: Tampilkan info barang terkait --}}
                        {{-- <div class="text-xs text-gray-400 mt-2 flex items-center gap-2">
                            <img src="https://placehold.co/40x40" class="w-8 h-8 rounded object-cover">
                            <span>Rice Cooker Cosmos</span>
                        </div> --}}
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center text-gray-500 p-10 bg-white rounded-2xl shadow">
                <i data-lucide="message-circle" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                <p>Belum ada percakapan.</p>
                <p class="text-sm mt-2">Mulai percakapan dengan menekan tombol "Hubungi Pendonasi" di halaman barang.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection