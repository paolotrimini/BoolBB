<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Apartment;
use App\Message;
use App\Service;
use App\Sponsorship;

class GuestController extends Controller {
    
    public function index(){

        date_default_timezone_set('Europe/Rome');
        $currentDate = date('Y-m-d H:i:s', time());

        $allApartments = DB::table('sponsorships')
            -> join('apartment_sponsorship', 'sponsorships.id', '=', 'apartment_sponsorship.sponsorship_id')
            -> join('apartments', 'apartment_sponsorship.apartment_id', '=', 'apartments.id')
            -> where('end_date', '>', $currentDate)
            -> get();

        $apartments = [];
        
        foreach ($allApartments as $apartment) {
            
            !in_array($apartment, $apartments) ? $apartments [] = $apartment : '';
        }

        return view('pages.home', compact('apartments'));
    }

    public function search(Request $request) {

        return view('pages.apartmentSearch');
    }

    public function showApartment($id){

        $apartment = Apartment::findOrFail($id);

        $services = $apartment -> services() -> wherePivot('apartment_id', '=', $id) -> get();

        return view('pages.apartmentShow', compact('apartment', 'services'));
    }

    public function storeMessage(Request $request, $id) {

        $apartment = Apartment::findOrFail($id);
        
        $validation = $request -> validate([
            'email' => 'required|string|max:128',
            'text' => 'required|string|min:20|max:255',
            'apartment_id' => 'required|exists:App\Apartment,id|integer'
        ]);

        $apartment = Apartment::findOrFail($request -> apartment_id);

        $message = Message::make($validation);
        $message -> apartment() -> associate($apartment);
        $message -> save();

        return view ('pages.messageSent', compact('apartment'));
    }

}
