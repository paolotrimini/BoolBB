<?php

use Illuminate\Database\Seeder;

use App\Apartment;
use App\Message;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        factory(Message::class, 50) -> make()
            -> each(function($message) {

        $apartment = Apartment::inRandomOrder() -> first();
        $message -> apartment() -> associate($apartment);
        $message -> save();
    });
    }
}
