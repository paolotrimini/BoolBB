<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

use Braintree;

use Auth;
use App\Apartment;
use App\Message;
use App\Service;
use App\Sponsorship;
use App\Statistic;
use App\User;

class LoggedController extends Controller {
    
    public function __construct() {
        
        $this->middleware('auth');
    }

    private function braintree(){
        $gateway = new Braintree\Gateway([
            'environment' => env('BT_ENVIRONMENT'),
            'merchantId' => env('BT_MERCHANT_ID'),
            'publicKey' => env('BT_PUBLIC_KEY'),
            'privateKey' => env('BT_PRIVATE_KEY')
        ]);

        return $gateway;
    }

    public function dashboard($id) {

        if(Auth::id() == $id) {
            
            date_default_timezone_set('Europe/Rome');
            $currentDate = date('Y-m-d H:i:s', time());

            $user = User::findOrFail($id);
            $apartments = Apartment::where('user_id', 'LIKE', $id) -> orderBy('city') -> get();

            return view('pages.dashboard', compact('user', 'apartments', 'currentDate'));
        } 

        return redirect() -> route('index');
    }
    
    public function createApartment() {

        $services = Service::all();

        return view('pages.apartmentCreate', compact('services'));
    }
    public function storeApartment(Request $request) {

        $validation = $request -> validate([
            'title' => 'required|string|max:256',
            'cover_image' => 'required|mimes:jpeg,png,jpg',
            'description' => 'required|string',
            'rooms_number' => 'required|integer',
            'beds_number' => 'required|integer',
            'bathrooms_number' => 'required|integer',
            'area' => 'required|integer',
            'address' => 'required|string|min:1',
            'city' => 'required|string|min:1',
            'country' => 'required|string|min:1',
            'postal_code' => 'required|string|min:5|max:5',
            'user_id' => 'required|exists:App\User,id|integer',
            'service_id.*' => 'required_if:current,1|distinct|exists:App\Service,id|integer'       
        ]);

        $user = User::findOrFail($request -> get('user_id'));

        $img = $request -> file('cover_image');
        $imgExt = $img -> getClientOriginalExtension();

        $imgNewName = time() . rand(0, 1000) . '.' . $imgExt;
        $folder = '/images/';

        $imgFile = $img -> storeAs($folder, $imgNewName, 'public');

        $removeComma = str_replace(',', '', $request['address']);
        $address = str_replace(' ', '%20', $removeComma);
        $city = $request['city'];
        $country = $request['country'];

        $query = $address . '%20' . $city . '%20' . $country;
        $response = Http::get('https://api.tomtom.com/search/2/geocode/' . $query . '.JSON?key=e221oCcENGoXZRDyweSTg7PnYGiEXO82')['results'][0]['position'];

        $apartment = Apartment::make($validation);
        $apartment -> user() -> associate($user);
        $apartment -> cover_image = $imgNewName;
        $apartment -> latitude = $response['lat'];
        $apartment -> longitude = $response['lon'];
        $apartment -> save();

        $apartment -> services() -> attach($request -> get('service_id'));
        $apartment -> save();

        return redirect() -> route('dashboard', Auth::id());
    }

    public function editApartment($id) {
        
        $apartment = Apartment::findOrFail($id);
        
        if(Auth::id() == $apartment -> user_id) {

            $user = User::findOrFail($apartment -> user_id);
            $services = Service::all();
    
            return view('pages.apartmentEdit', compact('apartment', 'user', 'services'));
        }

        return redirect() -> route('dashboard', Auth::id());
            
    }

    public function updateApartment(Request $request, $id) {

        $validation = $request -> validate([
            'title' => 'required|string|max:256',
            'cover_image' => 'mimes:jpeg,png,jpg',
            'description' => 'required|string',
            'rooms_number' => 'required|integer',
            'beds_number' => 'required|integer',
            'bathrooms_number' => 'required|integer',
            'area' => 'required|integer',
            'address' => 'required|string|min:1',
            'city' => 'required|string|min:1',
            'country' => 'required|string|min:1',
            'postal_code' => 'required|string|min:5|max:5',
            'user_id' => 'required|exists:App\User,id|integer',
            'service_id.*' => 'required_if:current,1|distinct|exists:App\Service,id|integer'       
        ]);

        $user = User::findOrFail($request -> get('user_id'));

        $apartment = Apartment::findOrFail($id);
        $apartment -> update($validation);

        $apartment -> user() -> associate($user);

        if ($request -> file('cover_image')) {
            
            $img = $request -> file('cover_image');
            $imgExt = $img -> getClientOriginalExtension();

            $imgNewName = time() . rand(0, 1000) . '.' . $imgExt;
            $folder = '/images/';

            $imgFile = $img -> storeAs($folder, $imgNewName, 'public');

            $apartment -> cover_image = $imgNewName;
        }

        $removeComma = str_replace(',', '', $request['address']);
        $address = str_replace(' ', '%20', $removeComma);
        $city = $request['city'];
        $country = $request['country'];

        $query = $address . '%20' . $city . '%20' . $country;
        $response = Http::get('https://api.tomtom.com/search/2/geocode/' . $query . '.JSON?key=e221oCcENGoXZRDyweSTg7PnYGiEXO82')['results'][0]['position'];

        $apartment -> latitude = $response['lat'];
        $apartment -> longitude = $response['lon'];
        
        $apartment -> save();

        $apartment -> services() -> sync($request -> get('service_id'));

        return redirect() -> route('dashboard', Auth::id());
    }

    public function destroyApartment($id) {

        $apartment = Apartment::findOrFail($id);

        if(Auth::id() == $apartment -> user_id) {

            $userId = $apartment -> user_id;
            $apartment -> delete();
            $apartment -> save();
    
            return redirect() -> route('dashboard', Auth::id());
        }

        return redirect() -> route('dashboard', Auth::id());
    }

    public function myApartment($id) {

        $apartment = Apartment::findOrFail($id);

        if(Auth::id() == $apartment -> user_id) {

            $messages = Message::where('apartment_id', 'LIKE', $id) -> orderBy('created_at') -> get();
            $statistics = Statistic::where('apartment_id', 'LIKE', $id) -> orderBy('created_at') -> get();
            $services = $apartment -> services() -> wherePivot('apartment_id', '=', $id) -> get();
    
            return view('pages.myApartment', compact('apartment', 'messages', 'statistics', 'services'));
        }

        return redirect() -> route('dashboard', Auth::id());
    }

    public function sponsorshipPayment($id) {

        $apartment = Apartment::findOrFail($id);
        
        foreach ($apartment -> sponsorships as $apartRel) {

            date_default_timezone_set('Europe/Rome');
            $currentDate = date('Y-m-d H:i:s', time());

            $endDate = $apartRel -> pivot -> end_date;
            $endDateFormat = date('Y-m-d H:i:s', strtotime($endDate));
            
            if ($currentDate < $endDateFormat) {

                return redirect() -> route('dashboard', Auth::id());
            }
        }

        if(Auth::id() == $apartment -> user_id) {

            $gateway = $this -> braintree();
            $token = $gateway->ClientToken()->generate();
    
            $sponsorships = Sponsorship::all();
    
            return view('pages.sponsorshipPayment', compact('token', 'sponsorships', 'apartment'));
        }

        return redirect() -> route('index');
    }

    public function paymentCheckout(Request $request, $id) {

        $gateway = $this -> braintree();
        $amount = $request -> amount;
        $nonce = $request -> payment_method_nonce;

        $result = $gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $sponsorship = Sponsorship::where('price', 'LIKE', $request -> amount);

        $apartment = Apartment::findOrFail($id);

        if ($result->success) {

            $transaction = $result->transaction;

            $getSponsorship = Sponsorship::where('price', 'LIKE', $request -> amount) -> get();

            $sponsorship = $getSponsorship[0];

            date_default_timezone_set('Europe/Rome');
            $startDate = date('Y-m-d H:i:s', time());

            if ($sponsorship -> id == 1) {
        
                $endDate = date("Y-m-d H:i:s", strtotime('+24 hours', strtotime($startDate)));
            } else if ($sponsorship -> id == 2) {

                $endDate = date("Y-m-d H:i:s", strtotime('+48 hours', strtotime($startDate)));
            } else {

                $endDate = date("Y-m-d H:i:s", strtotime('+144 hours', strtotime($startDate)));
            }

            $apartment -> sponsorships() -> attach($sponsorship, ['start_date' => $startDate, 'end_date' => $endDate]);
            $apartment -> save();

            $newStartDate = date("d-m-Y", strtotime($startDate));
            $newEndDate = date("d-m-Y", strtotime($endDate));

            return view('pages.successCheckout', compact('apartment', 'sponsorship', 'newStartDate', 'newEndDate'));
        } else {

            $errorString = "";

            foreach($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }

            $error = $result -> message;
            
            return back() -> withErrors('an error occured with the message' . $result -> message);
        } 
    }

    /* public function successCheckout($id) {

        $apartment = Apartment::findOrFail($id);

        return redirect() -> route('dashboard', $apartment -> user_id);
    } */
}