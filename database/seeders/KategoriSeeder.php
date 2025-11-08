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

        // Kosongkan tabel dulu jika perlu
        // Kategori::truncate(); 

        foreach ($kategoris as $nama) {
            Kategori::create([
                'nama_kategori' => $nama,
                'slug' => Str::slug($nama)
            ]);
        }
    }
}