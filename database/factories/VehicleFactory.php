<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $merkTipe = [
            'Honda' => ['Vario 150', 'Beat', 'PCX', 'Brio', 'HR-V'],
            'Toyota' => ['Avanza', 'Innova', 'Fortuner', 'Yaris'],
            'Yamaha' => ['NMAX', 'Aerox', 'Mio']
        ];
        
        $merk = $this->faker->randomKey($merkTipe);
        $tipe = $this->faker->randomElement($merkTipe[$merk]);

        return [
            // client_id akan diisi otomatis di Seeder
            'nopol' => $this->faker->unique()->bothify('B #### ???'),
            'no_rangka' => strtoupper($this->faker->bothify('MH1??????????????')),
            'no_mesin' => strtoupper($this->faker->bothify('JFE1E???????')),
            'merk' => $merk,
            'tipe' => $tipe,
            'tahun_pembuatan' => $this->faker->numberBetween(2015, 2023),
            'warna' => $this->faker->safeColorName(),
            'nama_pemilik' => $this->faker->name('id_ID'),
        ];
    }
}

