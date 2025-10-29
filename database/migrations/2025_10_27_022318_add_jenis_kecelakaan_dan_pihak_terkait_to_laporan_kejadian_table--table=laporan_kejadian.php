<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ISI FUNGSI UP() ANDA DENGAN INI:
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
        // ISI FUNGSI DOWN() ANDA DENGAN INI:
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->dropColumn(['jenis_kecelakaan', 'pihak_terkait']);
        });
    }
};