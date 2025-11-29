@extends('layouts.app')

{{-- tombol back diarahkan ke daftar chat --}}
@section('showBackButton', true)
@section('backButtonUrl', route('chat.index'))

@section('content')
@php
    use Illuminate\Support\Str;

    // Cari pesan terakhir dari lawan bicara (bukan dari saya)
    $lastIncoming = $messages
        ->where('sender_id', '!=', Auth::id())
        ->last();

    $smartReplies    = [];
    $smartTypeLabel  = null;
    $smartTypeIcon   = 'sparkles';

    if (!$lastIncoming) {
        // Belum ada pesan dari lawan → fokus ke sapaan awal
        $smartTypeLabel = 'Sapaan awal';
        $smartReplies = [
            'Halo kak, saya tertarik dengan barang yang kakak donasikan 🙌',
            'Apakah barangnya masih tersedia kak?',
            'Boleh tahu lokasi pengambilan di daerah mana ya kak?'
        ];
    } else {
        $text = Str::lower($lastIncoming->message ?? '');

        // ====== DETEKSI JENIS PESAN ======
        if (Str::contains($text, ['kapan', 'jam', 'hari apa', 'hari apa ya', 'jadwal', 'besok', 'lusa', 'hari ini', 'datang kapan'])) {
            // Pertanyaan / obrolan soal JADWAL
            $smartTypeLabel = 'Penjadwalan pengambilan';
            $smartTypeIcon  = 'calendar-clock';
            $smartReplies = [
                'Saya bisa datang besok, kira-kira jam berapa yang cocok kak? 😊',
                'Hari apa saja biasanya kakak tersedia untuk pengambilan?',
                'Kalau hari ini atau besok sore, apakah boleh ambil barangnya kak?'
            ];
        } elseif (Str::contains($text, ['dimana', 'di mana', 'lokasi', 'alamat', 'maps', 'map', 'shareloc', 'share loc', 'pin lokasi'])) {
            // Pertanyaan / obrolan soal LOKASI
            $smartTypeLabel = 'Detail lokasi';
            $smartTypeIcon  = 'map-pin';
            $smartReplies = [
                'Boleh minta alamat lengkap atau pin lokasi Google Maps-nya kak? 🙏',
                'Apakah lokasinya bisa dijangkau dengan kendaraan umum kak?',
                'Kalau boleh, kirim share location supaya saya tidak tersesat kak 😄'
            ];
        } elseif (Str::contains($text, ['masih ada', 'masih tersedia', 'ready', 'kosong', 'sudah diambil', 'sudah ada yang ambil'])) {
            // Tentang ketersediaan barang
            $smartTypeLabel = 'Ketersediaan barang';
            $smartTypeIcon  = 'package-open';
            $smartReplies = [
                'Kalau masih tersedia, saya ingin sekali ambil barangnya kak 🙌',
                'Kalau sudah ada yang ambil, tidak apa-apa kak, terima kasih infonya ya 😊',
                'Kalau belum ada yang ambil, kapan saya boleh datang kak?'
            ];
        } elseif (Str::contains($text, ['terima kasih', 'makasih', 'matur nuwun', 'thank you', 'thanks'])) {
            // Ucapan terima kasih
            $smartTypeLabel = 'Ucapan balasan';
            $smartTypeIcon  = 'heart-handshake';
            $smartReplies = [
                'Sama-sama kak, terima kasih juga sudah bersedia berdonasi 🙏',
                'Terima kasih kembali kak, semoga jadi berkah untuk kita semua 🤍',
                'Sama-sama kak, semoga barangnya bermanfaat ya 😊'
            ];
        } elseif (Str::contains($text, ['foto', 'gambar', 'dokumentasi', 'kondisi', 'real pict', 'real pictnya'])) {
            // Minta / bahas foto barang
            $smartTypeLabel = 'Permintaan foto';
            $smartTypeIcon  = 'image';
            $smartReplies = [
                'Boleh kirimkan satu atau dua foto terbaru kondisinya kak? 😊',
                'Kalau ada foto tambahan bagian yang lecet atau rusak, boleh sekalian kak 🙏',
                'Terima kasih kak, fotonya sangat membantu untuk lihat kondisi barang.'
            ];
        } else {
            // Default: percakapan umum
            $smartTypeLabel = 'Balasan umum';
            $smartTypeIcon  = 'message-circle';
            $smartReplies = [
                'Baik kak, terima kasih informasinya 🙏',
                'Siap kak, saya akan menyesuaikan dengan jadwal & ketentuan dari kakak 😊',
                'Kalau ada hal lain yang perlu saya siapkan, boleh diinformasikan ya kak.'
            ];
        }
    }
@endphp

<div class="max-w-4xl mx-auto h-[calc(100vh-120px)] flex flex-col">

    {{-- Header chat --}}
    <section class="relative overflow-hidden rounded-3xl bg-white/90 border border-slate-200 shadow-sm mb-4">
        <div class="absolute inset-x-10 -top-20 h-32 bg-gradient-to-r from-blue-500/20 via-sky-400/20 to-cyan-400/10 blur-2xl"></div>
        <div class="relative px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Avatar lawan bicara --}}
                <div class="relative">
                    @if($otherUser->foto_profil)
                        <img class="h-11 w-11 sm:h-12 sm:w-12 rounded-full object-cover ring-2 ring-blue-100 shadow-sm"
                             src="{{ asset('uploads/avatars/' . $otherUser->foto_profil) }}" alt="Foto Profil">
                    @else
                        <div class="h-11 w-11 sm:h-12 sm:w-12 rounded-full bg-gradient-to-br from-blue-600 to-sky-500 text-white flex items-center justify-center font-semibold text-sm sm:text-base ring-2 ring-blue-100 shadow-sm">
                            {{ strtoupper(substr($otherUser->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif

                    {{-- dot online (dummy) --}}
                    <span class="absolute -bottom-0.5 -right-0.5 inline-flex h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                </div>

                <div>
                    <p class="text-sm sm:text-[15px] font-semibold text-slate-900">
                        {{ $otherUser->nama_lengkap }}
                    </p>
                    <p class="text-[11px] text-slate-400">
                        @<span>{{ $otherUser->username }}</span> • Pesan pribadi
                    </p>
                </div>
            </div>

            {{-- mini badge --}}
            <div class="hidden sm:flex flex-col items-end gap-1">
                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-[11px] text-slate-500">
                    <i data-lucide="sparkles" class="w-3 h-3"></i>
                    <span>Smart reply aktif</span>
                </div>
                @if($smartTypeLabel)
                    <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-[10px] text-blue-700 border border-blue-100">
                        <i data-lucide="{{ $smartTypeIcon }}" class="w-3 h-3"></i>
                        <span>Saran untuk: {{ $smartTypeLabel }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Area pesan --}}
    <section class="flex-1 relative rounded-3xl bg-slate-50/90 border border-slate-200 shadow-inner overflow-hidden flex flex-col">
        {{-- background dekor --}}
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -bottom-24 -left-10 w-52 h-52 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -top-24 -right-16 w-60 h-60 bg-cyan-100 rounded-full blur-3xl opacity-60"></div>
        </div>

        {{-- pesan scrollable --}}
        <div id="message-container" class="flex-1 overflow-y-auto px-3 sm:px-5 py-4 sm:py-5 space-y-3 text-sm">
            @forelse ($messages as $message)
                @php
                    $isMe = $message->sender_id == Auth::id();
                @endphp

                @if ($isMe)
                    {{-- Pesan saya --}}
                    <div class="flex justify-end">
                        <div class="max-w-[80%] sm:max-w-[65%] rounded-2xl rounded-br-sm bg-blue-600 text-white px-3.5 py-2.5 shadow-sm">
                            <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
                            <span class="block mt-1 text-[10px] text-blue-100 text-right">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @else
                    {{-- Pesan lawan --}}
                    <div class="flex items-end gap-2 max-w-full">
                        <div class="hidden sm:block flex-shrink-0">
                            <div class="h-7 w-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-500">
                                {{ strtoupper(substr($otherUser->nama_lengkap, 0, 1)) }}
                            </div>
                        </div>
                        <div class="max-w-[80%] sm:max-w-[65%] rounded-2xl rounded-bl-sm bg-white/95 text-slate-800 px-3.5 py-2.5 shadow-sm border border-slate-100">
                            <p class="whitespace-pre-line break-words">{{ $message->message }}</p>
                            <span class="block mt-1 text-[10px] text-slate-400 text-right">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center text-slate-500 px-6 py-10">
                    <div class="h-14 w-14 rounded-2xl bg-white/70 border border-slate-200 flex items-center justify-center mb-3">
                        <i data-lucide="message-square" class="w-7 h-7 text-slate-400"></i>
                    </div>
                    <p class="text-sm font-medium">Mulai percakapan</p>
                    <p class="text-xs mt-1 max-w-xs">
                        Sapa dulu dengan kalimat yang sopan sebelum menanyakan detail barang ya 🙌
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Input + Smart Reply --}}
        <div class="border-t border-slate-200 bg-white/90 px-3 sm:px-4 pt-2.5 pb-3 sm:pb-3.5">

            {{-- Smart Reply chips --}}
            @if(!empty($smartReplies))
                <div class="flex items-center gap-2 overflow-x-auto pb-1 mb-2 hide-scrollbar">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 text-[10px] sm:text-[11px] text-slate-500 border border-slate-200">
                        <i data-lucide="{{ $smartTypeIcon }}" class="w-3 h-3"></i>
                        <span>{{ $smartTypeLabel ?: 'Balasan cepat' }}</span>
                    </span>

                    @foreach($smartReplies as $reply)
                        <button
                            type="button"
                            class="smart-reply-chip inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium
                                   bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 hover:-translate-y-[1px] transition"
                            data-reply="{{ e($reply) }}"
                        >
                            <i data-lucide="zap" class="w-3 h-3"></i>
                            <span class="whitespace-nowrap">{{ $reply }}</span>
                        </button>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('chat.store', $otherUser->id) }}" method="POST" id="chat-form">
                @csrf
                <div class="flex items-end gap-2 sm:gap-3">
                    <div class="flex-1 relative">
                        <textarea
                            name="message"
                            id="message-input"
                            rows="1"
                            class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50/80 px-3.5 py-2.5 pr-10 text-sm text-slate-800
                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                            placeholder="Tulis pesan yang ramah..."
                            autocomplete="off"
                        >{{ old('message') }}</textarea>
                        <button type="button"
                                class="absolute right-2.5 bottom-2 inline-flex items-center justify-center h-7 w-7 rounded-full text-slate-400 hover:text-blue-600 transition"
                                tabindex="-1">
                            <i data-lucide="smile" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-blue-600 text-white shadow-md
                                   hover:bg-blue-700 hover:shadow-lg active:scale-[0.97] transition flex-shrink-0">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Auto-scroll ke pesan terakhir
    const container = document.getElementById('message-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    const input = document.getElementById('message-input');
    const form  = document.getElementById('chat-form');

    if (input) {
        // Auto-resize textarea
        const autoResize = () => {
            input.style.height = 'auto';
            input.style.height = input.scrollHeight + 'px';
        };
        autoResize();
        input.addEventListener('input', autoResize);

        // Kirim pesan dengan Enter (Shift+Enter untuk baris baru)
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim() !== '') {
                    form.submit();
                }
            }
        });
    }

    // Smart Reply click handler
    const chips = document.querySelectorAll('.smart-reply-chip');
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            if (!input) return;
            const reply = chip.getAttribute('data-reply') || '';

            if (!reply) return;

            // Kalau input masih kosong → langsung isi
            if (!input.value.trim()) {
                input.value = reply;
            } else {
                // Kalau sudah ada teks → tambahkan di belakang dengan spasi
                const trimmed = input.value.replace(/\s+$/, '');
                input.value = trimmed + (trimmed.endsWith('\n') ? '' : ' ') + reply;
            }

            input.focus();
            input.dispatchEvent(new Event('input')); // trigger auto-resize
        });
    });
});
</script>
@endpush
@endsection
