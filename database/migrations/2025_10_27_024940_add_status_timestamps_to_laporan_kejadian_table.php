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
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable()->after('status_laporan');
            $table->timestamp('verified_at')->nullable()->after('sent_at');
            $table->timestamp('completed_at')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kejadian', function (Blueprint $table) {
            $table->dropColumn(['sent_at', 'verified_at', 'completed_at']);
        });
    }
};
