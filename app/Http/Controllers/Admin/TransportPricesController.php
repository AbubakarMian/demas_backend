<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use Illuminate\Http\Request;

class TransportPricesController extends Controller
{
    public function index()
    {
        return view('admin.price.index');

    }
    public function get_car_prices(Request $request)
    {
        $data = [];
        $transport_type_arr = Transport_Type::get()->toArray();
        $journey_arr = Journey::get()->toArray();
        $slot_arr = Journey_Slot::get()->toArray();

      

        foreach ($slot_arr as $slot_key => $slot) {
            # code...

            foreach ($journey_arr as $journey_key => $journey) {
                # code...

                foreach ($transport_type_arr as $transport_type_key => $transport_type) {

                    $transport_type_id = $transport_type['id'];
                    $transport_journey_slot_id = $slot['id'];

                    $transport_prices_obj = TransportPrices::
                    where('transport_type_id', $transport_type_id)
                    ->where('journey_slot_id', $transport_journey_slot_id)
                    ->first(); // Use 'first' to retrieve a single record
                
                if (!$transport_prices_obj) {
                    // dd('hi');
                    $transport_prices_obj = new TransportPrices();
                    $transport_prices_obj->transport_type_id = $transport_type_id;
                    $transport_prices_obj->journey_slot_id = $transport_journey_slot_id;
                    $transport_prices_obj->price = 0;
                    $transport_prices_obj->save();
                }
                else{
                    // dd($data, $transport_prices_obj);
                }
                    $single_data = [
                        'transport_prices_id' => $transport_prices_obj->id,
                        'price' => $transport_prices_obj->price,
                        'transport_type' => $transport_type,
                        'journey' => $journey,
                        'slot' => $slot,
                    ];
                    $data[] = $single_data;

                }
            }
        }
        // dd($transport_type_arr, $journey_arr, $slot_arr, $data);
        $priceData['data'] = $data;
        echo json_encode($priceData);
    }

    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = TransportPrices::find($transport_Prices_id);
        $Transport_Prices->price = $request->price;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }

    
    
}