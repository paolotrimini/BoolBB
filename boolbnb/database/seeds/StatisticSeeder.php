<?php

use Illuminate\Database\Seeder;

use App\Apartment;
use App\Statistic;

class StatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        factory(Statistic::class, 500) -> make()
            -> each(function($statistic) {

        $apartment = Apartment::inRandomOrder() -> first();
        $statistic -> apartment() -> associate($apartment);
        $statistic -> save();;
        });
    }
}