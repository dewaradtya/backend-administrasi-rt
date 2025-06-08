<?php

namespace Database\Seeders;

use App\Models\Resident;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $i) {
            Resident::create([
                'name'  => $faker->name(),
                'ktp_photo'  => 'ktp/' . $faker->uuid . '.jpg',
                'status'     => $faker->randomElement(['tetap', 'kontrak']),
                'phone'      => $faker->phoneNumber(),
                'is_married' => $faker->boolean(),
            ]);
        }
    }
}
