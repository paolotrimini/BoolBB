<?php

use Illuminate\Database\Seeder;

use App\Apartment;
use App\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        factory(Service::class, 10) -> create()
            -> each(function($service) {

            $apartment = Apartment::inRandomOrder() 
                        -> limit(rand(0, 70))
                        -> get();
            $service -> apartments() -> attach($apartment);
            $service -> save();
        });
    }
}
