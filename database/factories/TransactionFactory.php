<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $status = ['Belum Diproses', 'Dokumen Lengkap', 'Menunggu Pembayaran', 'Sedang Diproses Samsat', 'Selesai'];
        
        return [
            // vehicle_id akan diisi di Seeder
            'invoice_no' => 'INV-' . $this->faker->unique()->numerify('202608####'),
            'jenis_layanan' => $this->faker->randomElement(['Pajak Tahunan', 'Pajak 5 Tahunan', 'Mutasi']),
            'total_biaya' => $this->faker->randomElement([250000, 500000, 1500000, 3000000]),
            'status_proses' => $this->faker->randomElement($status),
            'tgl_masuk' => $this->faker->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
        ];
    }
}
