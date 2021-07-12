<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker){

    $services = [
        [
            'name' => 'WiFi',
            'icon' => 'fas fa-wifi',

        ],
        [
            'name' => 'Posto Macchina',
            'icon' => 'fas fa-parking',
        ],
        [
            'name' => 'Piscina',
            'icon' => 'fas fa-swimmer',
        ],
        [
            'name' => 'Portineria',
            'icon' => 'fas fa-house-user',
        ],
        [
            'name' => 'Sauna',
            'icon' => 'fas fa-hot-tub',
        ],
        [
            'name' => 'Cucina',
            'icon' => 'fas fa-utensils',
        ],
        [
            'name' => 'Riscaldamento',
            'icon' => 'fas fa-thermometer-full',
        ],
        [
            'name' => 'Aria Condizionata',
            'icon' => 'fas fa-fan',
        ],
        [
            'name' => 'Colazione',
            'icon' => 'fas fa-coffee',
        ],
        [
            'name' => 'TV',
            'icon' => 'fas fa-tv',
        ]
    ];

    $index= $faker -> unique() -> numberBetween(0, 9);

    $service = $services[$index];

    return [
        'name' => $service['name'],
        'icon' => $service['icon']
    ];
});