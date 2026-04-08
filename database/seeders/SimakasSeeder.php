<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimakasSeeder extends Seeder
{
    public function run(): void
    {
        // Masukkan data Members dari file lama Baginda
        DB::table('members')->insert([
            ['name' => 'Ucup', 'division' => 'PIP', 'angkatan' => '2024', 'phone' => '08888283728373', 'status' => 'aktif'],
            ['name' => 'Roy', 'division' => 'PSDM', 'angkatan' => '2024', 'phone' => '08888283728373', 'status' => 'aktif'],
            ['name' => 'Deni', 'division' => 'Akademik', 'angkatan' => '2024', 'phone' => '0831388832383', 'status' => 'aktif'],
            ['name' => 'Fach', 'division' => 'Mulmed', 'angkatan' => '2024', 'phone' => '088821238361', 'status' => 'aktif'],
        ]);

        // Masukkan data Income awal
        DB::table('income')->insert([
            ['description' => 'proposal', 'amount' => 2000000.00, 'date' => '2026-03-02'],
        ]);
    }
}
