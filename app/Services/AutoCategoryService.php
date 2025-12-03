<?php

namespace App\Services;

use App\Models\Kategori;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AutoCategoryService
{
    /**
     * Mencoba menebak kategori_id berdasarkan nama & deskripsi barang.
     *
     * Urutan:
     * 1. Rule based lokal (tanpa internet) → cocokkan kata kunci.
     * 2. OpenAI (kalau API key ada & request sukses).
     * 3. Kalau semua gagal → null (nanti ditangani di controller dengan fallback).
     *
     * @return int|null  id kategori yang valid, atau null kalau benar-benar gagal.
     */
    public function guessCategoryId(string $namaBarang, string $deskripsi): ?int
    {
        $kategoris = Kategori::all(['id', 'nama_kategori']);

        if ($kategoris->isEmpty()) {
            Log::warning('AutoCategoryService: tabel kategoris kosong.');
            return null;
        }

        // ==============================
        // 1. RULE-BASED LOKAL (CEPAT)
        // ==============================
        $text = Str::lower($namaBarang . ' ' . $deskripsi);

        // a) Cek: kalau nama kategori muncul di teks (contoh: kategori = "Pakaian")
        foreach ($kategoris as $kategori) {
            $namaKat = Str::lower($kategori->nama_kategori);

            if (Str::contains($text, $namaKat)) {
                Log::info('AutoCategoryService: Kena rule based nama kategori.', [
                    'kategori_id'   => $kategori->id,
                    'nama_kategori' => $kategori->nama_kategori,
                ]);

                return $kategori->id;
            }
        }

        // b) Mapping kata kunci → label kategori (nama kategori kira-kira mengandung kata ini)
        $keywordMap = [
    // 1. Pakaian
    'pakaian' => [
        'baju', 'kaos', 'kemeja', 'celana', 'rok', 'jaket', 'hoodie',
        'dress', 'gamis', 'jilbab', 'kerudung', 'sweater', 'kain',
        'seragam', 'almamater',
    ],

    // 2. Olahraga (termasuk sepatu olahraga)
    'olahraga'  => [
        'sepatu bola', 'sepatu futsal', 'sepatu lari', 'sepatu olahraga',
        'sepatu sport', 'sneakers', 'running shoes', 'training shoes',
        'bola', 'futsal', 'basket', 'badminton', 'raket', 'jersey',
        'matras', 'skipping', 'barbel',
    ],

    // 3. Perabotan (termasuk perabot dapur & gelas)
    'perabot' => [
        'meja', 'kursi', 'lemari', 'sofa', 'rak', 'kasur', 'dipan',
        'spring bed', 'bufet', 'lemari tv',
        'gelas', 'cangkir', 'piring', 'mangkuk', 'sendok', 'garpu',
        'teko', 'botol', 'wadah', 'tupperware',
        'kompor', 'wajan', 'panci', 'rice cooker', 'magic com',
    ],

    // 4. Elektronik
    'elektronik' => [
        'hp', 'handphone', 'smartphone', 'android', 'iphone',
        'laptop', 'notebook', 'komputer', 'pc',
        'tv', 'televisi', 'speaker', 'headset', 'earphone', 'earbud', 'earbuds',
        'charger', 'powerbank',
        'kulkas', 'lemari es', 'mesin cuci', 'setrika',
        'kipas', 'kipas angin', 'blender', 'microwave',
        'monitor', 'printer',
    ],

    // 5. Bayi dan Anak
    'bayi' => [
        'bayi', 'stroller', 'kereta bayi', 'popok', 'diaper',
        'dot', 'empeng', 'botol susu', 'perlak', 'selimut bayi',
        'baju bayi', 'pampers',
        'mainan bayi', 'playmat',
    ],

    // 6. Aksesoris
    'aksesoris' => [
        'tas', 'ransel', 'backpack', 'tas selempang', 'sling bag',
        'dompet', 'pouch',
        'sabuk', 'ikat pinggang',
        'kalung', 'gelang', 'anting', 'cincin',
        'jam tangan', 'kacamata', 'topi',
    ],

    // 7. Alat Musik
    'alat musik' => [
        'gitar', 'gitar listrik', 'gitar akustik', 'bass',
        'pianika', 'piano', 'keyboard', 'organ',
        'biola', 'cello', 'ukulele',
        'drum', 'drum set', 'cajon',
        'harmonika', 'seruling', 'flute', 'klarinet',
    ],

    // 8. Alat Tulis
    'alat tulis' => [
        'pulpen', 'pen', 'bolpoin',
        'pensil', 'pensil warna', 'crayon',
        'buku tulis', 'buku catatan', 'notebook',
        'spidol', 'marker', 'stabilo', 'highlighter',
        'penghapus', 'tip ex', 'tipp ex',
        'penggaris', 'mistar',
    ],
];


        foreach ($keywordMap as $label => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($text, $keyword)) {
                    // Cari kategori yang nama_kategori-nya mengandung label ini
                    $match = $kategoris->first(function ($k) use ($label) {
                        return Str::contains(Str::lower($k->nama_kategori), $label);
                    });

                    if ($match) {
                        Log::info('AutoCategoryService: Kena rule based keyword → label.', [
                            'keyword'       => $keyword,
                            'label'         => $label,
                            'kategori_id'   => $match->id,
                            'nama_kategori' => $match->nama_kategori,
                        ]);

                        return $match->id;
                    }
                }
            }
        }

        // ==============================
        // 2. OPENAI (JIKA API KEY ADA)
        // ==============================
        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            Log::warning('AutoCategoryService: OPENAI_API_KEY belum diset, skip panggilan API.');
            return null;
        }

        // Susun daftar kategori yang jelas untuk dikirim ke model
        $kategoriListText = $kategoris
            ->map(fn ($k) => $k->id . ' - ' . $k->nama_kategori)
            ->implode("\n");

        $systemPrompt = <<<SYS
Kamu adalah sistem yang mengklasifikasikan barang donasi ke salah satu kategori di bawah ini.

Balas SELALU dalam format JSON valid:
{"id": <id_kategori_atau_null>}

Aturan:
- Pilih HANYA SATU kategori yang paling sesuai.
- Jika tidak yakin sama sekali, gunakan {"id": null}.
- Jangan menambahkan teks lain di luar JSON.

Daftar kategori (id - nama):
{$kategoriListText}
SYS;

        $userPrompt = <<<USER
Nama barang: {$namaBarang}

Deskripsi:
{$deskripsi}
USER;

        try {
            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'    => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content'   => $userPrompt],
                    ],
                    'temperature' => 0.1,
                ]);

            if (!$response->successful()) {
                Log::warning('AutoCategoryService: request ke OpenAI gagal.', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $content = $response->json('choices.0.message.content');

            Log::info('AutoCategoryService: raw response dari OpenAI', [
                'content' => $content,
            ]);

            if (!is_string($content) || trim($content) === '') {
                return null;
            }

            // Bersihkan kemungkinan ```json ... ``` wrapper
            $clean = trim($content);
            $clean = preg_replace('/^```(json)?/i', '', $clean);
            $clean = preg_replace('/```$/', '', $clean);
            $clean = trim($clean);

            $data = json_decode($clean, true);

            $id = null;

            if (is_array($data) && array_key_exists('id', $data)) {
                $id = $data['id'];
            } else {
                // Fallback: cari angka pertama dalam teks
                if (preg_match('/\b(\d+)\b/', $clean, $matches)) {
                    $id = (int) $matches[1];
                }
            }

            if ($id === null || $id === '' || $id === 'null') {
                return null;
            }

            $id = (int) $id;

            // Pastikan id kategori ini benar-benar ada di DB
            $exists = Kategori::where('id', $id)->exists();

            if (!$exists) {
                Log::warning('AutoCategoryService: id kategori dari OpenAI tidak ditemukan di database.', [
                    'id' => $id,
                ]);
                return null;
            }

            Log::info('AutoCategoryService: sukses pakai OpenAI.', [
                'kategori_id' => $id,
            ]);

            return $id;
        } catch (\Throwable $e) {
            Log::error('AutoCategoryService: exception saat memanggil OpenAI', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
