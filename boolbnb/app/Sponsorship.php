<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [

        'price',
        'duration',
    ];

    public function orders(){

        return $this -> hasMany(Order::class);
    }

    public function apartments(){

        return $this -> belongsToMany(Apartment::class)->withPivot('start_date', 'end_date');
    }
}
