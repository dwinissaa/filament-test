<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Satker>
 */
class SatkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_satker'=>'1212',
            'nama_satker'=>'BPS Kabupaten Deli Serdang',
            'alamat_satker'=>'Jl Santai'
        ];
    }
}
