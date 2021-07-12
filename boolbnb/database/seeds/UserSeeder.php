<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $users = [
            [
                'firstname' => 'Lorenzo',
                'lastname' => 'Fratini',
                'date_of_birth' => '1992-06-13',
                'email' => 'lorenzo.fratini@gmail.com',
                'password' => bcrypt('asdasdasd'),
                'remember_token' => Str::random(10)
            ],
            [
                'firstname' => 'Diego',
                'lastname' => 'Barbaresco',
                'date_of_birth' => '1990-01-01',
                'email' => 'diego.barbaresco@gmail.com',
                'password' => bcrypt('asdasdasd'),
                'remember_token' => Str::random(10)
            ],
            [
                'firstname' => 'Benito',
                'lastname' => 'Cassa',
                'date_of_birth' => '1990-01-01',
                'email' => 'benito.cassa@gmail.com',
                'password' => bcrypt('asdasdasd'),
                'remember_token' => Str::random(10)
            ],
            [
                'firstname' => 'Paolo',
                'lastname' => 'Trimi',
                'date_of_birth' => '1990-01-01',
                'email' => 'paolo.trimi@gmail.com',
                'password' => bcrypt('asdasdasd'),
                'remember_token' => Str::random(10)
            ],
            [
                'firstname' => 'Alessandro',
                'lastname' => 'Tibaldo',
                'date_of_birth' => '1990-01-01',
                'email' => 'alessandro.tibaldo@gmail.com',
                'password' => bcrypt('asdasdasd'),
                'remember_token' => Str::random(10)
            ]
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
