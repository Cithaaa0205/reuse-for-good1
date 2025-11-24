<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 flex flex-col md:flex-row gap-6">
    <!-- Gambar Barang -->
    <div class="flex-shrink-0">
        <img src="{{ asset('uploads/barang/' . $req->barangDonasi->foto_barang_utama) }}" 
             alt="{{ $req->barangDonasi->nama_barang }}" 
             class="w-full md:w-48 h-48 object-cover rounded-xl shadow-sm">
    </div>

    <!-- Info Detail -->
    <div class="flex-grow flex flex-col justify-between">
        <div>
            <!-- Header: Nama Barang & Label Status -->
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-xl font-bold text-gray-800">{{ $req->barangDonasi->nama_barang }}</h3>
                @if($status === 'menunggu')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600">Menunggu</span>
                @elseif($status === 'diterima')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-600">Diterima</span>
                @elseif($status === 'ditolak')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Ditolak</span>
                @endif
            </div>

            <!-- Info Pemohon -->
            <div class="flex items-center gap-3 mb-3">
                @if($req->penerima->foto_profil)
                    <img src="{{ asset('uploads/avatars/' . $req->penerima->foto_profil) }}" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr($req->penerima->nama_lengkap, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $req->penerima->nama_lengkap }}</p>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $req->penerima->alamat ?? 'Yogyakarta' }}
                    </p>
                </div>
            </div>

            <!-- Kontak Info -->
            <div class="text-xs text-gray-500 space-y-1 mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $req->penerima->nomor_telepon }}
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="mail" class="w-3 h-3"></i> {{ $req->penerima->email }}
                </div>
            </div>

            <!-- Alasan / Pesan -->
            <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-600 italic border-l-4 border-gray-300">
                "{{ $req->alasan_permintaan ?? 'Saya sangat membutuhkan barang ini untuk keperluan sehari-hari...' }}"
            </div>
        </div>

        <!-- Tombol Aksi (Hanya muncul di tab Menunggu) -->
        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-3">
            @if($status === 'menunggu')
                <form action="{{ route('request.updateStatus', ['requestBarang' => $req->id, 'status' => 'Disetujui']) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg flex items-center gap-2 transition">
                        <i data-lucide="check" class="w-4 h-4"></i> Terima
                    </button>
                </form>

                <form action="{{ route('request.updateStatus', ['requestBarang' => $req->id, 'status' => 'Ditolak']) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-bold rounded-lg flex items-center gap-2 transition">
                        <i data-lucide="x" class="w-4 h-4"></i> Tolak
                    </button>
                </form>
            @endif

            <!-- Tombol Chat (Selalu Muncul) -->
            <a href="{{ route('chat.show', $req->penerima->id) }}" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-lg flex items-center gap-2 transition">
                <i data-lucide="message-circle" class="w-4 h-4"></i> Chat
            </a>
        </div>
    </div>
</div>