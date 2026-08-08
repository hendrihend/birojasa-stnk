<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('1671############'),
            'nama_lengkap' => $this->faker->name('id_ID'),
            'no_whatsapp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
        ];
    }
}

