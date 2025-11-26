<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_donasis', function (Blueprint $table) {
            // Hanya tambahkan kolom jika belum ada
            if (!Schema::hasColumn('barang_donasis', 'provinsi')) {
                $table->string('provinsi')->nullable()->after('kondisi');
            }

            if (!Schema::hasColumn('barang_donasis', 'kabupaten')) {
                $table->string('kabupaten')->nullable()->after('provinsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('barang_donasis', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kabupaten']);
        });
    }
};
