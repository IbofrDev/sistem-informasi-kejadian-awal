<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom PT ke tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom nama PT setelah kolom 'nama'
            if (!Schema::hasColumn('users', 'pt')) {
                $table->string('pt')->nullable()->after('nama');
            }
        });
    }

    /**
     * Kembalikan (rollback) perubahan migrasi ini.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom kalau migrasi di-rollback
            if (Schema::hasColumn('users', 'pt')) {
                $table->dropColumn('pt');
            }
        });
    }
};