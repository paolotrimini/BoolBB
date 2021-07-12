<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    
    $emails = [
        'Luca.Neri@gmail.com',
        'Marco.Rossi@gmail.com',
        'Francesca.Bianchi@gmail.com',
        'Guybrush.Threepwood@gmail.com',
        'Simone.Icardi@gmail.com',
        'Alessandro.Sainato@gmail.com',
        'Olga.Demina@gmail.com',
        'Gianluca.Lomarco@gmail.com',
        'Alessio.Vietri@gmail.com'
    ];

    return [
        'email' => $emails[rand(0, 8)],
        'text' => $faker -> text
    ];
});
