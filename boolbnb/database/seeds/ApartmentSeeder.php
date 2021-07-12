<?php

use Illuminate\Database\Seeder;

use App\Apartment;
use App\User;
use App\Sponsorship;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        factory(Apartment::class, 82) -> create()
            -> each(function($apartment) {
        
                if (rand(0, 1)) {

                    $startDates = [
                        '2021-01-03',
                        '2021-03-10',
                        '2021-05-26',
                        date("Y-m-d H:i:s", time())
                    ];
        
                    $startDate = $startDates[rand(0, 3)];
        
                    $sponsorship = Sponsorship::inRandomOrder() -> first();
        
                    if ($sponsorship -> id == 1) {
        
                        $endDate = date("Y-m-d H:i:s", strtotime('+24 hours', strtotime($startDate)));
                    } else if ($sponsorship -> id == 2) {
        
                        $endDate = date("Y-m-d H:i:s", strtotime('+48 hours', strtotime($startDate)));
                    } else {
        
                        $endDate = date("Y-m-d H:i:s", strtotime('+144 hours', strtotime($startDate)));
                    }
        
                    $apartment -> sponsorships() -> attach($sponsorship, ['start_date' => $startDate, 'end_date' => $endDate]);
                    $apartment -> save();
                }
            }
        );
    }
}
