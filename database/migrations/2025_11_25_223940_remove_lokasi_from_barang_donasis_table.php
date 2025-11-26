<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('barang_donasis', function (Blueprint $table) {
        if (Schema::hasColumn('barang_donasis', 'lokasi')) {
            $table->dropColumn('lokasi');
        }
    });
}

public function down()
{
    Schema::table('barang_donasis', function (Blueprint $table) {
        if (!Schema::hasColumn('barang_donasis', 'lokasi')) {
            $table->string('lokasi')->nullable();
        }
    });
}
};
