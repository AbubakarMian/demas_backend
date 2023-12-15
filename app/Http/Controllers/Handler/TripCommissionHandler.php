<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Order;
use App\Models\SaleAgent;
use App\Models\SalesAgentTripPrice;
use App\Models\Settings;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TripCommissionHandler
{
    use Common, APIResponse;

    public function create_sale_agent_trip_prices($transport_prices=[],$sale_agents=[]) // order with order_details
    {
        if(!count($transport_prices)){
            $transport_prices = TransportPrices::get();
        }
        if(!count($sale_agents)){
            $sale_agents = SaleAgent::where('commision_type', Config::get('constants.sales_agent.commission_types.agreed_trip_rate'))->get();
        }
        
        foreach ($transport_prices as $transport_price_key => $transport_price) {            
            foreach ($sale_agents as $sale_agent_key => $sale_agent) {
                $transport_prices_obj = SalesAgentTripPrice::where('transport_price_id', $transport_price->id)
                    ->where('user_sale_agent_id', $sale_agent->user_id)
                    ->first();
                    if(!$transport_prices_obj){
                        $transport_prices_obj = new SalesAgentTripPrice();
                        $transport_prices_obj->transport_price_id = $transport_price->id;
                        $transport_prices_obj->user_sale_agent_id = $sale_agent->user_id;
                        $transport_prices_obj->journey_id = $transport_price->journey_id;
                        $transport_prices_obj->slot_id = $transport_price->slot_id;
                        $transport_prices_obj->transport_type_id = $transport_price->transport_type_id;
                        $transport_prices_obj->is_default = $transport_price->is_default;
                        $transport_prices_obj->commission = 0;
                        $transport_prices_obj->price = $transport_price->price;
                        $transport_prices_obj->save();
                    }
                   
                } 
            
        }
    }
    
    public function create_travel_agent_trip_prices($transport_prices=[],$sale_agents=[]) // order with order_details
    {
        if(!count($transport_prices)){
            $transport_prices = TransportPrices::get();
        }
        if(!count($sale_agents)){
            $travel_agents = Travel_Agent::get();
        }
        
        foreach ($transport_prices as $transport_price_key => $transport_price) {            
            foreach ($travel_agents as $sale_agent_key => $travel_agent) {
                $transport_prices_obj = TravelAgentCommission::where('transport_price_id', $transport_price->id)
                    ->where('user_travel_agent_id', $travel_agent->user_id)
                    ->first();
                    if(!$transport_prices_obj){
                        $transport_prices_obj = new TravelAgentCommission();
                        $transport_prices_obj->transport_price_id = $transport_price->id;
                        $transport_prices_obj->user_travel_agent_id = $travel_agent->user_id;
                        $transport_prices_obj->journey_id = $transport_price->journey_id;
                        $transport_prices_obj->slot_id = $transport_price->slot_id;
                        $transport_prices_obj->transport_type_id = $transport_price->transport_type_id;
                        $transport_prices_obj->is_default = $transport_price->is_default;
                        $transport_prices_obj->commission = 0;
                        $transport_prices_obj->price = $transport_price->price;
                        $transport_prices_obj->save();
                    }
                   
                } 
            
        }
    }
}
