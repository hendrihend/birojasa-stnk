<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\STNKRecord;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@birojasa.com',
            'password' => Hash::make('password123'), 
        ]);

        // 2. Buat 20 Klien Dummy beserta relasinya
        Client::factory(20)->create()->each(function ($client) {
            
            // Setiap klien secara acak memiliki 1 sampai 3 kendaraan
            $jumlahKendaraan = rand(1, 3);
            
            for ($i = 0; $i < $jumlahKendaraan; $i++) {
                // Buat Kendaraan
                $vehicle = Vehicle::factory()->create([
                    'client_id' => $client->id
                ]);

                // Buat 1 Record STNK Aktif untuk kendaraan tersebut
                STNKRecord::factory()->create([
                    'vehicle_id' => $vehicle->id
                ]);

                // Secara acak, buatkan transaksi (seolah-olah sedang/pernah diurus)
                if (rand(0, 1)) { 
                    Transaction::factory()->create([
                        'vehicle_id' => $vehicle->id
                    ]);
                }
            }
        });
    }
}