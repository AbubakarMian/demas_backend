<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function get_all(Request $request){

        if($request->transport_type_id){
            $cars = Transport::
            with('transport_type')
            ->where('transport_type_id',$request->transport_type_id)->paginate(500);
        }
        else{
            $cars = Transport::
            with('transport_type')->paginate(500);
        }
        $cars = $cars->items();
        return $this->sendResponse(200,$cars);
    }

    public function car_details($car_id){
        $car = Transport::with('transport_type')->find($car_id);
        return $this->sendResponse(200,$car);
    }
}
