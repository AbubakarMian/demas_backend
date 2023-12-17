<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\TripCommissionHandler;
use App\Models\Driver;
use App\Models\DriverCommission;
use App\Models\Journey;
use App\Models\Slot;
use App\Models\Transport_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class DriverCommissionController extends Controller
{
    public function index()
    {
        $journey_list = Journey::pluck('name','id');
        $slot_list = Slot::pluck('name','id');
        $transport_type_list = Transport_Type::pluck('name','id');
        $driver_list = Driver::with('user_obj')->get()->pluck('user_obj.name','user_obj.id');
        return view('admin.driver.commsion.index',compact(
            'journey_list',
            'slot_list',
            'transport_type_list',
            'driver_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        // $transport_types = Transport_Type::get();
        // $journies = Journey::get();
        // $slots = Slot::get();
        // $drivers = Driver::where('commision_type',
        //             Config::get('constants.driver.commission_types_keys.per_trip'))->get();
        // foreach ($slots as $slot_key => $slot) {
        //     foreach ($drivers as $driver_key => $driver) {
        //         foreach ($journies as $journey_key => $journey) {
        //             foreach ($transport_types as $transport_type_key => $transport_type) {
        //                 $transport_type_id = $transport_type->id;
        //                 $driver_commission_obj = DriverCommission::where('transport_type_id', $transport_type_id)
        //                     ->where('slot_id', $slot->id)
        //                     ->where('journey_id', $journey->id)
        //                     ->where('user_driver_id', $driver->user_id)
        //                     ->first(); // Use 'first' to retrieve a single record

        //                 if (!$driver_commission_obj) {
        //                     $driver_commission_obj = new DriverCommission();
        //                     $driver_commission_obj->user_driver_id = $driver->user_id;
        //                     $driver_commission_obj->journey_id = $journey->id;
        //                     $driver_commission_obj->slot_id = $slot->id;
        //                     $driver_commission_obj->transport_type_id = $transport_type_id;
        //                     $driver_commission_obj->is_default = $slot->is_default;
        //                     $driver_commission_obj->commission = 0;
        //                     $driver_commission_obj->save();
        //                 } else {
        //                     // dd($data, $transport_prices_obj);
        //                 }
        //             }
        //         }
        //     }
        // }
        
        // $trip_commission_handler = new TripCommissionHandler();
        // $trip_commission_handler->create_driver_trip_prices();
        
        $driver_commission = DriverCommission::with([
            'journey', 'slot', 'transport_type','user_obj'
        ])
        ->whereHas('driver',function($query){
            $query->where('commision_type',
            Config::get('constants.driver.commission_types_keys.per_trip'));
        })
        ;

        if($request->journey_id){
            $driver_commission = $driver_commission->where('journey_id',$request->journey_id);
        }
        if($request->slot_id){
            $driver_commission = $driver_commission->where('slot_id',$request->slot_id);
        }
        if($request->transport_type_id){
            $driver_commission = $driver_commission->where('transport_type_id',$request->transport_type_id);
        }
        if($request->user_driver_id){
            $driver_commission = $driver_commission->where('user_driver_id',$request->user_driver_id);
        }

        $priceData['data'] = $driver_commission->get();
        echo json_encode($priceData);
    }

    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = DriverCommission::find($transport_Prices_id);
        $Transport_Prices->commission = $request->commission;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }
}
