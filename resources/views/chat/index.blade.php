@extends('layouts.app')

@section('title', 'Pesan')
@section('showBackButton', true)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header kotak masuk --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-sky-500 to-cyan-400 text-white shadow-md">
        <div class="absolute inset-0 opacity-35 bg-[radial-gradient(circle_at_top_left,_#ffffff,_transparent_55%)]"></div>
        <div class="relative px-6 sm:px-8 py-4 sm:py-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] tracking-[0.16em] font-semibold uppercase text-blue-100">
                    Pesan & Percakapan
                </p>
                <h1 class="text-xl sm:text-2xl font-extrabold leading-tight">
                    Kotak Masuk
                </h1>
                <p class="text-[11px] sm:text-xs text-blue-50/90 mt-1">
                    Lanjutkan percakapan dengan pendonasi atau penerima barangmu. Smart reply akan membantumu balas lebih cepat ✨
                </p>
            </div>

            <div class="hidden sm:flex flex-col items-end gap-1 text-[11px]">
                <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full">
                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                    <span>{{ count($conversations) }} percakapan</span>
                </div>
                <div class="inline-flex items-center gap-1 bg-white/5 px-2.5 py-1 rounded-full text-blue-50/90">
                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                    <span>Smart reply di dalam chat</span>
                </div>
            </div>
        </div>
    </section>

    {{-- List percakapan --}}
    <section class="bg-white/95 rounded-3xl border border-slate-200 shadow-sm p-4 sm:p-5">
        @forelse ($conversations as $convo)
            @php
                $user = $convo['user'];
                $last = $convo['lastMessage'];

                // Format waktu singkat
                $timeLabel = $last->created_at->isToday()
                    ? $last->created_at->format('H:i')
                    : $last->created_at->format('d M');
            @endphp

            <a href="{{ route('chat.show', $user->id) }}"
               class="group block w-full rounded-2xl px-3 py-3.5 sm:px-4 sm:py-4 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all duration-150 mb-2 last:mb-0">
                <div class="flex items-start gap-3.5">

                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        @if($user->foto_profil)
                            <img class="h-11 w-11 sm:h-12 sm:w-12 rounded-full object-cover ring-2 ring-blue-100 shadow-sm"
                                 src="{{ asset('uploads/avatars/' . $user->foto_profil) }}" alt="Foto Profil">
                        @else
                            <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-full bg-gradient-to-br from-blue-600 to-sky-500 text-white flex items-center justify-center font-semibold text-sm sm:text-base ring-2 ring-blue-100 shadow-sm">
                                {{ strtoupper(substr($user->nama_lengkap, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Info chat --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm sm:text-[15px] font-semibold text-slate-900 truncate group-hover:text-blue-700">
                                    {{ $user->nama_lengkap }}
                                </p>
                                <p class="text-[11px] text-slate-400 truncate">
                                    @<span>{{ $user->username }}</span>
                                </p>
                            </div>
                            <span class="flex-shrink-0 text-[11px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $timeLabel }}
                            </span>
                        </div>

                        <p class="mt-1 text-xs sm:text-sm text-slate-600 truncate">
                            @if($last->sender_id == Auth::id())
                                <span class="font-semibold text-slate-700">Anda:&nbsp;</span>
                            @endif
                            {{ $last->message }}
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center text-slate-500 py-10 sm:py-12 flex flex-col items-center justify-center">
                <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                    <i data-lucide="message-circle" class="w-7 h-7 text-slate-400"></i>
                </div>
                <p class="text-sm font-medium">Belum ada percakapan</p>
                <p class="text-xs mt-1 max-w-xs mx-auto text-slate-400">
                    Mulai chat dengan menekan tombol <span class="font-semibold">"Hubungi Pendonasi"</span>
                    di halaman barang yang kamu minati.
                </p>
            </div>
        @endforelse
    </section>
</div>
@endsection
