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
        $journey_list = Journey::pluck('name','id');
        $slot_list = Slot::pluck('name','id');
        $transport_type_list = Transport_Type::pluck('name','id');
        return view('admin.price.index',compact(
            'journey_list',
            'slot_list',
            'transport_type_list'
        ));
    }

    public function get_car_prices(Request $request)
    {
        $transportPrices = TransportPrices::with([
            'journey','slot','transport_type'
        ])
        ->wherehas('journey')
        ->wherehas('slot')
        ->wherehas('transport_type')
        ;
        if($request->journey_id){
            $transportPrices = $transportPrices->where('journey_id',$request->journey_id);
        }
        if($request->slot_id){
            $transportPrices = $transportPrices->where('slot_id',$request->slot_id);
        }
        if($request->transport_type_id){
            $transportPrices = $transportPrices->where('transport_type_id',$request->transport_type_id);
        }
        $priceData['data'] = $transportPrices->get();

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
