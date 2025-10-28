<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->string('jenis_kecelakaan')->nullable()->after('isi_laporan');
            $table->string('pihak_terkait')->nullable()->after('jenis_kecelakaan');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->dropColumn(['jenis_kecelakaan', 'pihak_terkait']);
        });
    }
};