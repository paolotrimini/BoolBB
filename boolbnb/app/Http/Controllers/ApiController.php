<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Apartment;
use App\Message;
use App\Service;
use App\Statistic;


class ApiController extends Controller {
    
    public function getApartments($searchString) {

        $getApartments = Apartment::where('city', 'LIKE', '%' . $searchString . '%')->get();

        $apartments = [];

        foreach ($getApartments as $apartment) {
           
            foreach ($apartment -> sponsorships as $apartRel) {

                $endDate = $apartRel -> pivot -> end_date;

                date_default_timezone_set('Europe/Rome');
                $currentDate = date('m/d/Y H:i:s', time());
                $endDateFormat = date('m/d/Y H:i:s', strtotime($endDate));
                
                if ($currentDate < $endDateFormat) {

                    !in_array($apartment, $apartments) ? $apartments [] = $apartment : '';
                }
            }
        }
        
        foreach ($getApartments as $apartment) {
            
            !in_array($apartment, $apartments) ? $apartments [] = $apartment : '';
        }

        return response() -> json($apartments, 200);
    }

    public function getServices() {

        $services = Service::all();

        return response() -> json($services, 200);
    }

    public function filterApartments($searchString, $filterServices, $bedsRooms) {

        $apartments = Apartment::where('city', 'LIKE', '%' . $searchString . '%') -> get();

        if ($filterServices != 'noServices') {

            $filterServices = explode(',', $filterServices);
            
            $services = array();
            
            foreach ($filterServices as $data) {
                
                $services [] = intval($data);
            }
            
            $apartments = Apartment::whereHas('services', function($query) use($services)
            {
                $query -> whereIn('service_id', $services);
            }, "=", count($services))
            ->where('city', 'LIKE', '%' . $searchString . '%')
            ->get();
        }

        $filterBedsRooms = explode(',', $bedsRooms);
        $bedsRooms = [];
        
        foreach ($filterBedsRooms as $data) {
            
            $bedsRooms [] = intval($data);
        }

        $beds = $bedsRooms[0];
        $rooms = $bedsRooms[1];
        $filteredApartments = array();

        foreach ($apartments as $apartment) {

            if ($apartment -> beds_number >= $beds && $apartment -> rooms_number >= $rooms) {

                foreach ($apartment -> sponsorships as $apartRel) {
    
                    $endDate = $apartRel -> pivot -> end_date;
    
                    date_default_timezone_set('Europe/Rome');
                    $currentDate = date('m/d/Y H:i:s', time());
                    $endDateFormat = date('m/d/Y H:i:s', strtotime($endDate));
                    
                    if ($currentDate < $endDateFormat) {
    
                        !in_array($apartment, $filteredApartments) ? $filteredApartments [] = $apartment : '';
                    }
                }
            }
        }

        foreach ($apartments as $apartment) {

            if ($apartment -> beds_number >= $beds && $apartment -> rooms_number >= $rooms) {
                
                !in_array($apartment, $filteredApartments) ? $filteredApartments [] = $apartment : '';
            }
        }

        return response() -> json($filteredApartments, 200);
    }

    public function getViews($ip, $id) {

        $statistics = Statistic::all();

        foreach ($statistics as $statistic) {
            
            if ($statistic -> ip == $ip && $statistic -> apartment_id == $id) {
                
                return;
            }
        }

        $apartment = Apartment::findOrFail($id);

        $newStatistic = [
            'ip' => $ip
        ];

        $statistic = Statistic::make($newStatistic);
        $statistic -> apartment() -> associate($apartment);
        $statistic -> save();
    }

    public function getChartData($id, $year){

        $bottomLimit = strval($year - 1) . '12-31';
        $topLimit = strval($year) . '12-31';
        
        $apartment = Apartment::findOrFail($id);

        $statistics = Statistic::where('apartment_id', 'LIKE', $id)
                                -> where('created_at', '>', $bottomLimit)
                                -> where('created_at', '<=', $topLimit)
                                -> get();

        $messages = Message::where('apartment_id', 'LIKE', $id)
                                -> where('created_at', '>', $bottomLimit)
                                -> where('created_at', '<=', $topLimit)
                                -> get();
            
        $ordered_stats = array();
            
        foreach ($statistics as $statistic) {
                
            $formattedDate = date("n-Y", strtotime($statistic -> created_at));
            $statDate = explode("-", $formattedDate);
            list($month, $statYear) = $statDate;
            
            $ordered_stats[$statYear][$month][]= $statistic;
        };

        $stats = array();

        if (empty($ordered_stats)) {

            $stats = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        } else {

            for ($i=0; $i < 12; $i++) { 
                
                $newYear = strval($year);
                $newMonth = strval($i + 1);
                
                if (isset($ordered_stats[$newYear][$newMonth])) {

                    $stats []= count($ordered_stats[$newYear][$newMonth]);
                } else {

                    $stats []= 0;
                }
            }
        }
        
        $ordered_messages = array();
            
        foreach ($messages as $message) {
                
            $formattedDate = date("n-Y", strtotime($message -> created_at));
            $messageDate = explode("-", $formattedDate);
            list($month, $mexYear) = $messageDate;
            
            $ordered_messages[$mexYear][$month][]= $message;
        };

        $messages = array();

        if (empty($ordered_messages)) {

            $messages = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        } else {

            for ($i=0; $i < 12; $i++) { 
                
                $newYear = strval($year);
                $newMonth = strval($i + 1);
                
                if (isset($ordered_messages[$newYear][$newMonth])) {

                    $messages []= count($ordered_messages[$newYear][$newMonth]);
                } else {

                    $messages []= 0;
                }
            }
        }

        $chart = [

            'statistics' => $stats,
            'messages' => $messages,
        ];
        
        return response() -> json($chart, 200);
    }

}


