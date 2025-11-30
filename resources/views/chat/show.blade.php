@extends('layouts.app')

{{-- tombol back diarahkan ke daftar chat --}}
@section('showBackButton', true)
@section('backButtonUrl', route('chat.index'))

@section('content')
@php
    use Illuminate\Support\Str;

    // Cari pesan terakhir dari lawan bicara
    $lastIncoming = $messages
        ->where('sender_id', '!=', Auth::id())
        ->last();

    $smartReplies    = [];
    $smartTypeLabel  = null;
    $smartTypeIcon   = 'sparkles';

    if (!$lastIncoming) {
        $smartTypeLabel = 'Sapaan awal';
        $smartReplies = [
            'Halo kak, saya tertarik dengan barang yang kakak donasikan 🙌',
            'Apakah barangnya masih tersedia kak?',
            'Boleh tahu lokasi pengambilan di daerah mana ya kak?'
        ];
    } else {
        $text = Str::lower($lastIncoming->message ?? '');

        // LOGIKA SMART REPLY
        if (Str::contains($text, ['kapan', 'jam', 'hari apa', 'jadwal', 'besok', 'lusa'])) {
            $smartTypeLabel = 'Penjadwalan';
            $smartTypeIcon  = 'calendar-clock';
            $smartReplies = [
                'Saya bisa datang besok, kira-kira jam berapa yang cocok kak? 😊',
                'Hari apa saja biasanya kakak tersedia?',
                'Kalau hari ini atau besok sore, apakah boleh?'
            ];
        } elseif (Str::contains($text, ['dimana', 'lokasi', 'alamat', 'maps', 'shareloc'])) {
            $smartTypeLabel = 'Lokasi';
            $smartTypeIcon  = 'map-pin';
            $smartReplies = [
                'Boleh minta alamat lengkap atau pin lokasi Google Maps-nya kak? 🙏',
                'Apakah lokasinya bisa dijangkau kendaraan umum?',
                'Kalau boleh, kirim share location supaya saya tidak tersesat 😄'
            ];
        } elseif (Str::contains($text, ['masih ada', 'tersedia', 'ready', 'kosong', 'sudah diambil'])) {
            $smartTypeLabel = 'Ketersediaan';
            $smartTypeIcon  = 'package-open';
            $smartReplies = [
                'Kalau masih tersedia, saya ingin sekali ambil barangnya kak 🙌',
                'Kalau sudah ada yang ambil, tidak apa-apa kak, terima kasih infonya 😊',
                'Kapan saya boleh datang untuk ambil kak?'
            ];
        } elseif (Str::contains($text, ['terima kasih', 'makasih', 'thanks'])) {
            $smartTypeLabel = 'Balasan';
            $smartTypeIcon  = 'heart-handshake';
            $smartReplies = [
                'Sama-sama kak, terima kasih juga sudah berdonasi 🙏',
                'Terima kasih kembali kak, semoga berkah 🤍',
                'Sama-sama, semoga barangnya bermanfaat 😊'
            ];
        } elseif (Str::contains($text, ['foto', 'gambar', 'kondisi', 'real pict'])) {
            $smartTypeLabel = 'Foto';
            $smartTypeIcon  = 'image';
            $smartReplies = [
                'Boleh kirimkan foto terbaru kondisinya kak? 😊',
                'Kalau ada bagian yang lecet, boleh difotokan sekalian kak 🙏',
                'Terima kasih kak, fotonya sangat membantu.'
            ];
        } else {
            $smartTypeLabel = 'Balasan umum';
            $smartTypeIcon  = 'message-circle';
            $smartReplies = [
                'Baik kak, terima kasih informasinya 🙏',
                'Siap kak, saya sesuaikan dengan jadwal kakak 😊',
                'Ada lagi yang perlu saya siapkan kak?'
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

            {{-- Kanan: tombol Laporkan + info Smart Reply --}}
            <div class="flex flex-col items-end gap-2">
                {{-- Tombol Laporkan (pojok kanan atas) --}}
                @auth
                    <button
                        id="btn-report-user"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-semibold
                               bg-rose-50 text-rose-700 border border-rose-100 hover:bg-rose-100 hover:border-rose-200 transition"
                        title="Laporkan akun / percakapan ini"
                    >
                        <i data-lucide="flag" class="w-3 h-3"></i>
                        Laporkan
                    </button>
                @endauth

                {{-- Smart reply badge --}}
                <div class="hidden sm:flex flex-col items-end gap-1">
                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-[11px] text-slate-500">
                        <i data-lucide="sparkles" class="w-3 h-3"></i>
                        <span>Smart reply aktif</span>
                    </div>
                    @if($smartTypeLabel)
                        <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-[10px] text-blue-700 border border-blue-100">
                            <i data-lucide="{{ $smartTypeIcon }}" class="w-3 h-3"></i>
                            <span>Saran: {{ $smartTypeLabel }}</span>
                        </div>
                    @endif
                </div>
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
                    
                    // --- LOGIKA LINKIFICATION ---
                    $msgSafe = e($message->message);
                    $pattern = '/(https?:\/\/[^\s]+)/';

                    if ($isMe) {
                        $msgFormatted = preg_replace(
                            $pattern, 
                            '<a href="$1" target="_blank" rel="noopener noreferrer" class="underline font-bold text-blue-100 hover:text-white break-all inline-flex items-center gap-1"><i data-lucide="external-link" class="w-3 h-3"></i> $1</a>', 
                            $msgSafe
                        );
                    } else {
                        $msgFormatted = preg_replace(
                            $pattern, 
                            '<a href="$1" target="_blank" rel="noopener noreferrer" class="underline font-bold text-blue-600 hover:text-blue-800 break-all inline-flex items-center gap-1"><i data-lucide="external-link" class="w-3 h-3"></i> $1</a>', 
                            $msgSafe
                        );
                    }
                @endphp

                @if ($isMe)
                    {{-- Pesan saya --}}
                    <div class="flex justify-end">
                        <div class="max-w-[85%] sm:max-w-[70%] rounded-2xl rounded-br-sm bg-blue-600 text-white px-3.5 py-2.5 shadow-sm">
                            <div class="whitespace-pre-line break-words">{!! $msgFormatted !!}</div>
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
                        <div class="max-w-[85%] sm:max-w-[70%] rounded-2xl rounded-bl-sm bg-white/95 text-slate-800 px-3.5 py-2.5 shadow-sm border border-slate-100">
                            <div class="whitespace-pre-line break-words">{!! $msgFormatted !!}</div>
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
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-50 text-[10px] sm:text-[11px] text-slate-500 border border-slate-200 flex-shrink-0">
                        <i data-lucide="{{ $smartTypeIcon }}" class="w-3 h-3"></i>
                        <span>{{ $smartTypeLabel ?: 'Balasan cepat' }}</span>
                    </span>

                    @foreach($smartReplies as $reply)
                        <button
                            type="button"
                            class="smart-reply-chip inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-medium
                                   bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 hover:-translate-y-[1px] transition flex-shrink-0"
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
                            class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50/80 px-3.5 py-2.5 pr-20 text-sm text-slate-800
                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                            placeholder="Tulis pesan..."
                            autocomplete="off"
                        >{{ old('message') }}</textarea>
                        
                        {{-- Group Tombol: Share Loc & Smiley --}}
                        <div class="absolute right-2 bottom-2 flex items-center gap-1">
                            {{-- Tombol Share Location --}}
                            <button type="button" 
                                    id="btn-share-location"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition"
                                    title="Bagikan Lokasi Saya">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </button>

                            {{-- Tombol Smiley / Emoji --}}
                            <button type="button" 
                                    id="btn-emoji"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full text-slate-400 hover:text-yellow-500 hover:bg-yellow-50 transition" 
                                    title="Pilih Emoji">
                                <i data-lucide="smile" class="w-4 h-4"></i>
                            </button>
                        </div>
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

{{-- ===================== MODAL LAPORAN USER / CHAT ===================== --}}
@auth
<div id="report-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-report-close></div>

    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full mx-auto p-5 sm:p-6 border border-slate-100">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-500">
                    Laporkan Pengguna
                </p>
                <h2 class="text-lg font-bold text-slate-900">
                    Laporkan {{ $otherUser->nama_lengkap }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Gunakan fitur ini jika akun terasa mencurigakan, melakukan penipuan, spam, atau melanggar aturan platform.
                </p>
            </div>
            <button type="button" class="p-2 rounded-full hover:bg-slate-100 text-slate-400" data-report-close>
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="report-form" method="POST" action="{{ route('report.user', $otherUser->id) }}" class="space-y-4">
            @csrf
            <div class="space-y-2">
                <p class="text-xs font-medium text-slate-700">Pilih alasan cepat</p>
                <div class="flex flex-wrap gap-2">
                    @php
                        $quickReasons = [
                            'Spam / iklan berlebihan',
                            'Penipuan / meminta data pribadi / pembayaran',
                            'Info palsu atau menyesatkan',
                            'Konten tidak pantas atau mengganggu',
                            'Lainnya (jelaskan di kolom di bawah)'
                        ];
                    @endphp
                    @foreach($quickReasons as $reason)
                        <button type="button"
                                class="report-reason-btn inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-medium bg-slate-50 text-slate-700 border border-slate-200 hover:border-rose-300 hover:bg-rose-50/70 transition"
                                data-report-reason="{{ $reason }}">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <span class="whitespace-nowrap">{{ $reason }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="space-y-1">
                <label for="report-reason" class="text-xs font-medium text-slate-700">
                    Jelaskan singkat alasanmu <span class="text-rose-500">*</span>
                </label>
                <textarea id="report-reason"
                          name="reason"
                          rows="4"
                          required
                          class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-400 resize-none"></textarea>
                <p class="text-[11px] text-slate-400">
                    Laporanmu bersifat rahasia dan hanya akan digunakan untuk menjaga keamanan & kenyamanan platform.
                </p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-1">
                <button type="button" class="px-3 py-2 rounded-2xl text-xs font-semibold text-slate-500 hover:bg-slate-100 transition" data-report-close>
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-xs font-semibold bg-rose-600 text-white shadow-md hover:bg-rose-700 hover:shadow-lg transition">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

@push('scripts')
{{-- Load Emoji Button Library via CDN --}}
<script type="module">
    import { EmojiButton } from 'https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.2/dist/index.js';

    document.addEventListener('DOMContentLoaded', () => {
        const input    = document.getElementById('message-input');
        const form     = document.getElementById('chat-form');
        const btnEmoji = document.querySelector('#btn-emoji');

        // ==========================================
        // 1. Logic Emoji Picker
        // ==========================================
        if (btnEmoji && input) {
            const picker = new EmojiButton({
                position: 'top-end',
                theme: 'auto',
                autoHide: false,
                rows: 4,
                recentsCount: 10,
                i18n: {
                    search: 'Cari emoji...',
                    categories: {
                        recents: 'Sering dipakai',
                        smileys: 'Senyum & Emosi',
                        people: 'Orang',
                        animals: 'Hewan',
                        food: 'Makanan',
                        activities: 'Aktivitas',
                        travel: 'Travel',
                        objects: 'Objek',
                        symbols: 'Simbol',
                        flags: 'Bendera'
                    }
                }
            });

            picker.on('emoji', selection => {
                const text = input.value;
                input.value = text + selection.emoji;
                input.dispatchEvent(new Event('input'));
                input.focus();
            });

            btnEmoji.addEventListener('click', () => {
                picker.togglePicker(btnEmoji);
            });
        }

        // ==========================================
        // 2. Auto Scroll & Auto Resize
        // ==========================================
        const container = document.getElementById('message-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        if (input) {
            const autoResize = () => {
                input.style.height = 'auto';
                input.style.height = input.scrollHeight + 'px';
            };
            autoResize();
            input.addEventListener('input', autoResize);

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim() !== '') {
                        form.submit();
                    }
                }
            });
        }

        // ==========================================
        // 3. Smart Reply Chips
        // ==========================================
        const chips = document.querySelectorAll('.smart-reply-chip');
        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                if (!input) return;
                const reply = chip.getAttribute('data-reply') || '';
                if (!reply) return;

                if (!input.value.trim()) {
                    input.value = reply;
                } else {
                    const trimmed = input.value.replace(/\s+$/, '');
                    input.value = trimmed + (trimmed.endsWith('\n') ? '' : ' ') + reply;
                }

                input.focus();
                input.dispatchEvent(new Event('input'));
            });
        });

        // ==========================================
        // 4. Share Location
        // ==========================================
        const btnLocation = document.getElementById('btn-share-location');
        if (btnLocation && input) {
            btnLocation.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    alert("Browser kakak tidak mendukung fitur lokasi.");
                    return;
                }

                const originalIcon = btnLocation.innerHTML;
                btnLocation.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin text-blue-500"></i>';
                btnLocation.disabled = true;
                if (typeof lucide !== 'undefined') lucide.createIcons();

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const mapsLink = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
                        
                        const currentText = input.value.trim();
                        const textToAdd   = "Ini lokasi saya kak: " + mapsLink;
                        
                        input.value = currentText + (currentText ? '\n' : '') + textToAdd;
                        input.dispatchEvent(new Event('input'));
                        input.focus();

                        btnLocation.innerHTML = originalIcon;
                        btnLocation.disabled  = false;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    },
                    (error) => {
                        console.error(error);
                        let errorMsg = "Gagal mengambil lokasi.";
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMsg = "Mohon izinkan akses lokasi di browser kakak ya 🙏";
                        }
                        alert(errorMsg);
                        btnLocation.innerHTML = originalIcon;
                        btnLocation.disabled  = false;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                );
            });
        }

        // ==========================================
        // 5. Modal Laporkan User (header kanan atas)
        // ==========================================
        const reportModal = document.getElementById('report-modal');
        const reportBtn   = document.getElementById('btn-report-user');

        if (reportModal && reportBtn) {
            const reasonInput = document.getElementById('report-reason');

            const openModal = () => {
                reportModal.classList.remove('hidden');
                reportModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                if (reasonInput) {
                    reasonInput.focus();
                }
            };

            const closeModal = () => {
                reportModal.classList.add('hidden');
                reportModal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            reportBtn.addEventListener('click', openModal);

            reportModal.querySelectorAll('[data-report-close]').forEach(btn => {
                btn.addEventListener('click', closeModal);
            });

            reportModal.addEventListener('click', (e) => {
                if (e.target === reportModal) {
                    closeModal();
                }
            });

            // Chip alasan cepat
            reportModal.querySelectorAll('.report-reason-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const reason = btn.getAttribute('data-report-reason');
                    if (!reason) return;

                    if (!reasonInput.value.trim()) {
                        reasonInput.value = reason;
                    } else {
                        const trimmed = reasonInput.value.replace(/\s+$/, '');
                        reasonInput.value = trimmed + (trimmed.endsWith('\n') ? '' : '\n') + reason;
                    }
                    reasonInput.focus();
                });
            });
        }
    });
</script>
@endpush
@endsection
