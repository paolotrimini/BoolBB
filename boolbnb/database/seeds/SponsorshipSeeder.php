<?php

use Illuminate\Database\Seeder;

use App\Apartment;
use App\Sponsorship;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sponsorships = [
            [
                'price' => 2.99,
                'duration' => 24
            ],
            [
                'price' => 5.99,
                'duration' => 72
            ],
            [
                'price' => 9.99,
                'duration' => 144
            ]
        ];

        foreach ($sponsorships as $key => $sponsorship) {
            Sponsorship::create($sponsorship);
        }
    }
}
