<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'title',
        'cover_image',
        'description',
        'rooms_number',
        'beds_number',
        'bathrooms_number',
        'area',
        'address',
        'city',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'visible',
        'user_id',
    ];

    protected $dates = ['deleted_at'];

    public function images(){

        return $this -> hasMany(Image::class);
    }

    public function statistics(){

        return $this -> hasMany(Statistic::class);
    }

    public function user(){

        return $this -> belongsTo(User::class);
    }

    public function services(){

        return $this -> belongsToMany(Service::class);
    }

    public function messages(){

        return $this -> hasMany(Message::class);
    }

    public function sponsorships(){

        return $this -> belongsToMany(Sponsorship::class)->withPivot('start_date', 'end_date');
    }
}
