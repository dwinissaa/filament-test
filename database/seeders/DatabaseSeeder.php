<?php

namespace Database\Seeders;

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
            'role'=>'1'
        ]);

        Satker::factory()->create([
            'kode_satker'=> '1212',
            'nama_satker'=> 'BPS Kabupaten Deli Serdang',
            'alamat_satker'=> 'Jl santai',
        ]);
    }
}
