<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil SimakasSeeder agar data Ucup, Roy, dll. masuk ke database
        $this->call([
            SimakasSeeder::class,
        ]);
    }
}