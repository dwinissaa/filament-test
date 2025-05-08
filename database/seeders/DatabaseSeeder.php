<?php

namespace Database\Seeders;

use App\Models\Frekuensi;
use App\Models\Karakteristik;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Satker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => '1'
        ]);

        Satker::factory()->create([
            'id' => '1212',
            'nama_satker' => 'BPS Kabupaten Deli Serdang',
            'alamat_satker' => 'Jl santai',
        ]);

        $frekuensi_arr = [
            1 => 'Bulanan',
            2 => 'Triwulanan',
            3 => 'Semesteran',
            4 => 'Tahunan',
            5 => 'Sepuluh Tahunan',
        ];

        foreach ($frekuensi_arr as $key => $value) {
            Frekuensi::factory()->create([
                'id' => $key,
                'frekuensi' => $value,
            ]);
        }
        

        // Karakteristik::factory()->create([
            
        // ]);
    }
}


