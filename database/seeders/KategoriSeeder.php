<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Pakaian',
            'Olahraga',
            'Perabotan',
            'Elektronik',
            'Bayi dan Anak',
            'Aksesoris',
            'Buku',
            'Alat Tulis'
        ];

        // Opsional: Hapus data lama agar tidak duplikat saat seeding ulang
        // Kategori::truncate(); 

        foreach ($kategoris as $nama) {
            // Gunakan firstOrCreate agar tidak error duplikat jika dijalankan 2x
            Kategori::firstOrCreate(
                ['slug' => Str::slug($nama)], // Cek berdasarkan slug
                ['nama_kategori' => $nama]    // Data yang dibuat jika belum ada
            );
        }
        
        // BAGIAN $this->call() DIHAPUS DARI SINI!
    }
}