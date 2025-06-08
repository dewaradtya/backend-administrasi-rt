<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PaymentItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $payment = Payment::pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            $startDate = $faker->dateTimeBetween('-3 years', '-1 month');
            $endDate = $faker->dateTimeBetween($startDate, 'now');

            PaymentItem::create([
                'payment_id' => fake()->randomElement($payment),
                'type' => fake()->randomElement(['satpam', 'kebersihan']),
                'amount' => fake()->numberBetween(20000, 100000),
                'start_date'  => $startDate->format('Y-m-d'),
                'end_date'    => $endDate->format('Y-m-d'),
            ]);
        }
    }
}
