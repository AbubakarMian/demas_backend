<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function get_all(Request $request){

        $cars = Transport::with('transport_type');
        if($request->transport_type_id){
            $cars = $cars->where('transport_type_id',$request->transport_type_id);//->paginate(500);
        }
        // else{
        //     $cars = $cars;
        // }
        $cars = $cars->orderByDesc('created_at')->paginate(500);

        $cars->transform(function($item){
            $item->features = isset($item->features)?explode(',',$item->features):[];
            $item->booking =  isset($item->booking)?explode(',',$item->booking):[];
            $item->dontforget = isset($item->dontforget)?explode(',',$item->dontforget):[];
            return $item;
        });
        $cars = $cars->items();
        return $this->sendResponse(200,$cars);
    }

    public function car_details($car_id){
        $car = Transport::with('transport_type')->find($car_id);
        return $this->sendResponse(200,$car);
    }
}
