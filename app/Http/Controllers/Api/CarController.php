<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\TransportHandler;
use App\Models\Journey;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function get_all(Request $request){

        $transport_handler = new TransportHandler();
        $cars = $transport_handler->get_car_details($request);
        return $this->sendResponse(200,$cars);
    }
    
    public function get_cars_by_types(Request $request){

        $transport_handler = new TransportHandler();
        $transport_types = $transport_handler->get_cars_by_types($request);
        return $this->sendResponse(200,$transport_types);
    }

    public function car_details(Request $request,$car_id){
        $transport_handler = new TransportHandler();
        $cars = $transport_handler->get_car_details($request,$car_id);
        $car = $cars->first();
        return $this->sendResponse(200,$car);
    }
}
