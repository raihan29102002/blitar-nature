<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RatingFeedback;
use App\Models\User;
use App\Models\Wisata;
use Faker\Factory as Faker;

class RatingFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = range(3, 8);
        $wisatas = \App\Models\Wisata::all();

        foreach ($wisatas as $wisata) {
            $reviewCount = rand(5, 10); 

            for ($i = 0; $i < $reviewCount; $i++) {
                RatingFeedback::create([
                    'wisata_id' => $wisata->id,
                    'user_id' => $faker->randomElement($userIds),
                    'rating' => rand(1, 5),
                    'feedback' => $faker->realText(50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
