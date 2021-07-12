<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/getApartments/{searchString}', 'ApiController@getApartments')
    -> name('getApartments');

Route::get('/getServices', 'ApiController@getServices')
    -> name('getServices');

Route::post('/getViews/{ip}/{id}', 'ApiController@getViews')
    -> name('getViews');

Route::post('/filterApartments/{searchString}/{filterServices}/{bedsRooms}', 'ApiController@filterApartments')
    -> name('filterApartments');

Route::post('/getChartData/{id}/{year}', 'ApiController@getChartData')
    -> name('getChartData');
