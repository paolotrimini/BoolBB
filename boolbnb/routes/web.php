<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'GuestController@index')
    -> name('index');

Route::get('/apartment/{id}', 'GuestController@showApartment')
    -> name('showApartment');

Route::post('/message/store/{id}', 'GuestController@storeMessage')
    -> name('storeMessage');

Route::get('/search', 'GuestController@search')
    -> name('search');

Route::get('/dashboard/{id}', 'LoggedController@dashboard')
    -> name('dashboard');

Route::get('/createApartment', 'LoggedController@createApartment')
    -> name('createApartment');
Route::post('/apartment/store', 'LoggedController@storeApartment')
    -> name('storeApartment');
    
Route::get('/editApartment/{id}', 'LoggedController@editApartment')
    -> name('editApartment');
Route::post('/updateApartment/{id}', 'LoggedController@updateApartment')
    -> name('updateApartment');
    
Route::get('/deleteApartment/{id}', 'LoggedController@destroyApartment')
    -> name('destroyApartment');

Route::get('/myApartment/{id}', 'LoggedController@myApartment')
    -> name('myApartment');

Route::get('/sponsorshipPayment/{id}', 'LoggedController@sponsorshipPayment')
    -> name('sponsorshipPayment');

Route::post('/paymentCheckout/{id}', 'LoggedController@paymentCheckout')
    -> name('paymentCheckout');

Auth::routes();
