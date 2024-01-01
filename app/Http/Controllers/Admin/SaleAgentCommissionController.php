<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\TripCommissionHandler;
use App\Models\Journey;
use App\Models\SaleAgent;
use App\Models\SalesAgentTripPrice;
use App\Models\Slot;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SaleAgentCommissionController extends Controller
{

    public function index()
    {
        $journey_list = Journey::pluck('name', 'id');
        $slot_list = Slot::pluck('name', 'id');
        $transport_type_list = Transport_Type::pluck('name', 'id');
        $sale_agent_list = SaleAgent::with('user_obj')->get()->pluck('user_obj.name', 'user_obj.id');
        return view('admin.sale_agent_comission.commsion.index', compact(
            'journey_list',
            'slot_list',
            'transport_type_list',
            'sale_agent_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        $sale_agent_commission = SalesAgentTripPrice::with([
            'journey', 'slot', 'transport_type', 'user_obj','transport_price_obj'
        ])
        ->wherehas('journey')
        ->wherehas('slot')
        ->wherehas('transport_type')
        ->wherehas('transport_price_obj')
        // ->wherehas('slot',function($q){
        //     $q->where('end_date','<' ,time());
        // })
        ;

        if ($request->journey_id) {
            $sale_agent_commission = $sale_agent_commission->where('journey_id', $request->journey_id);
        }
        if ($request->slot_id) {
            $sale_agent_commission = $sale_agent_commission->where('slot_id', $request->slot_id);
        }
        if ($request->transport_type_id) {
            $sale_agent_commission = $sale_agent_commission->where('transport_type_id', $request->transport_type_id);
        }
        if ($request->user_sale_agent_id) {
            $sale_agent_commission = $sale_agent_commission->where('user_sale_agent_id', $request->user_sale_agent_id);
        }

        $priceData['data'] = $sale_agent_commission->get();
        echo json_encode($priceData);
    }

    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = SalesAgentTripPrice::find($transport_Prices_id);
        // $Transport_Prices->commission = $request->commission;
        $Transport_Prices->price = $request->price;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }
}
