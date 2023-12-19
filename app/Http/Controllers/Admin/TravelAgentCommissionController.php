<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Slot;
use App\Models\Transport_Type;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use Illuminate\Http\Request;


class TravelAgentCommissionController extends Controller
{
    public function index()
    {
        $journey_list = Journey::pluck('name','id');
        $slot_list = Slot::pluck('name','id');
        $transport_type_list = Transport_Type::pluck('name','id');
        $travel_agent_list = Travel_Agent::with('user_obj')->get()->pluck('user_obj.name','user_obj.id');
        return view('admin.travel_agent.commsion.index',compact(
            'journey_list',
            'slot_list',
            'transport_type_list',
            'travel_agent_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        $travel_agent_commission = $this->get_travel_agent_commission($request);
        $priceData['data'] = $travel_agent_commission;
        echo json_encode($priceData);
    }

    public function get_travel_agent_commission(Request $request){

        $travel_agent_commission = TravelAgentCommission::with([
            'journey',
            'slot',
            'transport_type',
            'user_obj',
            'sale_agent_commission'=>function($q){
                $q->join('sale_agent', 'sale_agent.user_id', '=', 'sales_agent_trip_price.user_sale_agent_id');
            }
        ]);

        if($request->journey_id){
            $travel_agent_commission = $travel_agent_commission->where('journey_id',$request->journey_id);
        }
        if($request->slot_id){
            $travel_agent_commission = $travel_agent_commission->where('slot_id',$request->slot_id);
        }
        if($request->transport_type_id){
            $travel_agent_commission = $travel_agent_commission->where('transport_type_id',$request->transport_type_id);
        }
        if($request->user_travel_agent_id){
            $travel_agent_commission = $travel_agent_commission->where('user_travel_agent_id',$request->user_travel_agent_id);
        }
        $travel_agent_commission = $travel_agent_commission->get();
        $travel_agent_commission->transform(function($travel_agent_commission_item){
            $sale_agent_commission_obj = null;
            if(isset($travel_agent_commission_item->sale_agent_commission[0])){
                $sale_agent_commission_obj = $travel_agent_commission_item->sale_agent_commission[0];
            }
            $travel_agent_commission_item->sale_agent_commission_obj = $sale_agent_commission_obj;
            unset($travel_agent_commission_item->sale_agent_commission);
            return $travel_agent_commission_item;
        });

        return $travel_agent_commission;
    }
    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = TravelAgentCommission::find($transport_Prices_id);
        // $Transport_Prices->commission = $request->commission;
        $Transport_Prices->price = $request->price;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }
}
