<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Slot;
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
        $journies = Journey::get();
        $slots = Slot::get();
        // $slot_arr = Journey_Slot::get();
        foreach ($slots as $slot_key => $slot) {
            foreach ($journies as $journey_key => $journey) {
                foreach ($transport_type_arr as $transport_type_key => $transport_type) {
                    $transport_type_id = $transport_type['id'];
                    // $transport_journey_slot_id = $slot['id'];
                    $transport_prices_obj = TransportPrices::where('transport_type_id', $transport_type_id)
                        ->where('slot_id', $slot->id)
                        ->where('journey_id', $journey->id)
                        ->first(); // Use 'first' to retrieve a single record

                    if (!$transport_prices_obj) {
                        $transport_prices_obj = new TransportPrices();
                        $transport_prices_obj->transport_type_id = $transport_type_id;
                        $transport_prices_obj->journey_id = $journey->id;
                        $transport_prices_obj->slot_id = $slot->id;
                        $transport_prices_obj->is_default = $slot->is_default;
                        $transport_prices_obj->price = 0;
                        $transport_prices_obj->save();
                    } else {
                        // dd($data, $transport_prices_obj);
                    }
                }
            }
        }
        $priceData['data'] = TransportPrices::with([
            'journey','slot','transport_type'
        ])->get();
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
