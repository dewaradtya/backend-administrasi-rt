<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\House;
use App\Models\InhabitantHistories;
use Faker\Factory as Faker;

class InhabitantHistoriesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $residentIds = Resident::pluck('id')->toArray();
        $houseIds = House::pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            $startDate = $faker->dateTimeBetween('-3 years', '-1 month');
            $endDate = $faker->boolean(70) ? $faker->dateTimeBetween($startDate, 'now') : null;

            InhabitantHistories::create([
                'resident_id' => $faker->randomElement($residentIds),
                'house_id'    => $faker->randomElement($houseIds),
                'start_date'  => $startDate->format('Y-m-d'),
                'end_date'    => $endDate ? $endDate->format('Y-m-d') : null,
            ]);
        }
    }
}
