<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggunakan class bernama, bukan 'return new class'
class CreateKategorisTable extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kategoris'); }
};