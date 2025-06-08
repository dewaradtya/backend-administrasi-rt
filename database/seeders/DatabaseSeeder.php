<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ResidentSeeder::class,
            HouseSeeder::class,
            InhabitantHistoriesSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
            PaymentItemSeeder::class
        ]);
    }
}
