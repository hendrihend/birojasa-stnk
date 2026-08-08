<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class STNKRecordFactory extends Factory
{
    public function definition(): array
    {
        return [
            // vehicle_id akan diisi di Seeder
            'no_stnk' => $this->faker->numerify('########'),
            // Kita buat tanggal jatuh tempo acak dari 30 hari yang lalu sampai 120 hari ke depan
            // Ini agar Dashboard Anda bisa menampilkan status "Expired", "H-14", dan "Aman"
            'tgl_jatuh_tempo_pajak' => $this->faker->dateTimeBetween('-30 days', '+120 days')->format('Y-m-d'),
            'tgl_habis_stnk' => $this->faker->dateTimeBetween('+1 years', '+4 years')->format('Y-m-d'),
            'status_aktif' => true,
        ];
    }
}
