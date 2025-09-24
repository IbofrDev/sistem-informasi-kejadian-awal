<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDbConnection extends Command
{
    protected $signature = 'db:check';
    protected $description = 'Checks the active database connection details used by Laravel';

    public function handle()
    {
        try {
            // Mengambil detail dari konfigurasi yang sedang aktif
            $connection = config('database.default');
            $host = config('database.connections.'.$connection.'.host');
            $port = config('database.connections.'.$connection.'.port');
            $database = config('database.connections.'.$connection.'.database');

            // Memastikan koneksi bisa dibuat
            DB::connection()->getPdo();

            $this->info("Laravel BERHASIL terhubung ke database dengan detail berikut:");
            $this->line("----------------------------------------------------");
            $this->info("Host     : " . $host);
            $this->info("Port     : " . $port);
            $this->info("Database : " . $database);
            $this->line("----------------------------------------------------");

        } catch (\Exception $e) {
            $this->error("Gagal terhubung ke database. Error: " . $e->getMessage());
        }

        return 0;
    }
}