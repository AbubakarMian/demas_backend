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
        // $journey_res = json_decode($transport_handler->get_journey($request));
        // if(!$journey_res){
        //     return $journey_res;
        // }
        
        // $journey = $journey_res->response;
        
        // $slot = $transport_handler->get_slot($request);
        
        // $cars = $transport_handler->get_cars($request,$journey,$slot);

        // $user = $request->attributes->get('user');
        // $travel_agent = Travel_Agent::where('user_id',$user->id)->first();

        // $cars = $transport_handler->transform_cars($journey,$slot,$cars,$travel_agent);
        $cars = $transport_handler->get_car_details($request);
        // dd($cars);
        // $cars = $cars->items();
        return $this->sendResponse(200,$cars);
    }

    public function car_details(Request $request,$car_id){
        // $car = Transport::with('transport_type')->find($car_id);
        $transport_handler = new TransportHandler();

        $cars = $transport_handler->get_car_details($request,$car_id);
        $car = $cars->first();
        return $this->sendResponse(200,$car);
    }
}
