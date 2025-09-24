<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LaporanKejadian>
 */
class LaporanKejadianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status_laporan' => $this->faker->randomElement(['dikirim', 'diverifikasi', 'selesai']),
            'nama_pelapor' => $this->faker->name(),
            'jabatan_pelapor' => $this->faker->randomElement(['Master/Nakhoda', 'C/O (Chief Officer)']),
            'telepon_pelapor' => $this->faker->phoneNumber(),
            'jenis_kapal' => $this->faker->randomElement(['KM (Kapal Motor)', 'MT (Motor Tanker)', 'TB (TUG Boat)']),
            'nama_kapal' => $this->faker->firstNameFemale() . ' ' . $this->faker->numberBetween(1, 99),
            'bendera_kapal' => $this->faker->countryCode(),
            'grt_kapal' => $this->faker->numberBetween(100, 5000),
            'pelabuhan_asal' => $this->faker->city(),
            'waktu_berangkat' => $this->faker->dateTimeThisYear(),
            'pelabuhan_tujuan' => $this->faker->city(),
            'estimasi_tiba' => $this->faker->dateTimeThisYear(),
            'pemilik_kapal' => $this->faker->company(),
            'kontak_pemilik' => $this->faker->phoneNumber(),
            'agen_lokal' => $this->faker->company(),
            'kontak_agen' => $this->faker->phoneNumber(),
            'jenis_muatan' => $this->faker->word(),
            'jumlah_muatan' => $this->faker->numberBetween(50, 2000) . ' Ton',
            'jumlah_penumpang' => $this->faker->numberBetween(0, 150),
            'posisi_lintang' => $this->faker->latitude() . ' S',
            'posisi_bujur' => $this->faker->longitude() . ' T',
            'tanggal_laporan' => $this->faker->dateTimeThisYear(),
            'isi_laporan' => $this->faker->paragraph(3),
        ];
    }
}
