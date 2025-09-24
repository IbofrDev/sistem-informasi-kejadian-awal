<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kejadian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status_laporan', ['dikirim', 'diverifikasi', 'selesai'])->default('dikirim');

            // Data Pelapor
            $table->string('nama_pelapor');
            $table->string('jabatan_pelapor');
            $table->string('telepon_pelapor');

            // Data Kapal
            $table->string('jenis_kapal');
            $table->string('nama_kapal');
            $table->string('nama_kapal_kedua')->nullable(); // Khusus Tug Boat
            $table->string('bendera_kapal');
            $table->integer('grt_kapal');
            $table->string('imo_number')->nullable();
            $table->string('pelabuhan_asal');
            $table->dateTime('waktu_berangkat');
            $table->string('pelabuhan_tujuan');
            $table->dateTime('estimasi_tiba');
            $table->string('pemilik_kapal');
            $table->string('kontak_pemilik');
            $table->string('agen_lokal');
            $table->string('kontak_agen');

            // Data Pilot & Muatan
            $table->string('nama_pandu')->nullable();
            $table->string('nomor_register_pandu')->nullable();
            $table->text('jenis_muatan');
            $table->string('jumlah_muatan'); // Menggunakan string untuk fleksibilitas (misal: "100 Ton", "5 Kontainer")
            $table->integer('jumlah_penumpang');

            // Posisi & Isi Laporan
            $table->string('posisi_lintang');
            $table->string('posisi_bujur');
            $table->text('isi_laporan');
            $table->dateTime('tanggal_laporan');

            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kejadian');
    }
};