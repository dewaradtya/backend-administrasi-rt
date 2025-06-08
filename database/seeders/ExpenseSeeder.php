<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = ['Perbaikan Jalan', 'Lampu Jalan', 'Air Bersih', 'Pembuangan Sampah', 'Listrik Umum', 'Kegiatan RT'];

        foreach (range(1, 20) as $i) {
            Expense::create([
                'name' => $faker->randomElement($categories),
                'amount' => $faker->numberBetween(10000, 500000),
                'description' => $faker->sentence(),
                'date' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ]);
        }
    }
}
