<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $residents = Resident::pluck('id')->toArray();
        $houses = House::pluck('id')->toArray();

        if (empty($residents) || empty($houses)) {
            $this->command->warn('Seeder Payment dilewati karena residents/houses kosong.');
            return;
        }

        foreach (range(1, 20) as $i) {
            Payment::create([
                'resident_id' => fake()->randomElement($residents),
                'house_id' => fake()->randomElement($houses),
                'total_amount' => fake()->numberBetween(20000, 100000),
                'note' =>  fake()->sentence(),
                'status' => fake()->randomElement(['lunas', 'belum lunas']),
                'payment_date' => fake()->date(),
            ]);
        }
    }
}
