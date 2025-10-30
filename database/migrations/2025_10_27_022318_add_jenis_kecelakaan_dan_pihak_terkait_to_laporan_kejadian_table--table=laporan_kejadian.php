<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // KITA PAKAI VERSI ANDA YANG LEBIH SPESIFIK (MENGGUNAKAN ->after())
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->string('jenis_kecelakaan')->nullable()->after('posisi_bujur');
            $table->string('pihak_terkait')->nullable()->after('jenis_kecelakaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // FUNGSI down() ANDA SUDAH BENAR, HANYA TANDA KONFLIKNYA DIHAPUS
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->dropColumn(['jenis_kecelakaan', 'pihak_terkait']);
        });
    }
};