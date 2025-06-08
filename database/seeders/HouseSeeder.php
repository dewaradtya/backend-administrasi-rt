<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HouseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $isOccupied = $faker->boolean;

            House::create([
                'house_number'     => 'No. ' . $faker->unique()->buildingNumber,
                'is_occupied'      => $isOccupied,
            ]);
        }
    }
}
