<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Order;
use App\Models\SaleAgent;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;

class CommissionHandler
{
    use Common, APIResponse;

    public function get_trip_price_details(
        Request $request,
        $pickup_id,
        $dropoff_id,
        $transport_type_id,
        $user_id,
        $pickupdate_time,
        $customer_collection_price
    ) {
        $user = Users::with(['sale_agent','travel_agent','driver'])->find($user_id);

        $slot = $this->get_slot($pickupdate_time);
        $journey = $this->get_journey($pickup_id, $dropoff_id);
        $journey_slot = $this->get_journey_slot($journey, $slot);
        $journey_price = $this->get_journey_price($journey->id, $slot->id,$transport_type_id,$user);
        $commission_details = $this->get_car_commission_details($slot, $journey,$journey_price, $transport_type_id, $user_id,$customer_collection_price);
        $response = new \stdClass();
        $response->journey_price = $journey_price;
        $response->commission_details = $commission_details;
        $response->journey_slot = $journey_slot;
        return $response;
    }

    public function get_journey_slot($journey, $slot){
        $journey_slot = Journey_Slot::where('journey_id',$journey->id)
                                        ->where('slot_id',$slot->id)
                                        ->first();
        return $journey_slot;
    }

    public function get_journey_price($journey_id, $slot_id,$transport_type_id,$user){

        if($user->travel_agent){
            $user_id = $user->id;
            $journey_price = TravelAgentCommission::with(['travel_agent' => ['user_obj', 'sale_agent.user_obj']])
            ->where('user_travel_agent_id', $user_id)
            ->where('journey_id', $journey_id)
            ->where('slot_id', $slot_id)
            ->where('transport_type_id', $transport_type_id)
            ->first();
        }
        else{
            $journey_price = TransportPrices::where('transport_type_id',$transport_type_id)
            ->where('journey_id',$journey_id)
            ->where('slot_id',$slot_id)
            ->first();
        }
        
        return $journey_price;
    }

    public function get_slot($pickupdate_time = 0)
    {
        $slot = Slot::where('start_date', '<=', $pickupdate_time)
            ->where('end_date', '>=', $pickupdate_time)
            ->orderbyDesc('created_at')
            ->first();

        if (!$slot) {
            $slot = Slot::where('start_date', '0')
                ->where('end_date', '0')
                ->first();
        }
        return $slot;
    }
    public function get_journey($pickup_id, $dropoff_id)
    {
        $journey = Journey::where('pickup_location_id', $pickup_id)
            ->where('dropoff_location_id', $dropoff_id)->first();

        return  $journey;
    }

    public function get_car_commission_details($slot, $journey,$journey_price, $transport_type_id, $user_id,$customer_collection_price)
    {
        $user = Users::with(['sale_agent','travel_agent','driver'])->find($user_id);
        $res = new \stdClass();
        $sale_agent_user_id = 0;
        $res->travel_agent_user_id = 0;
        $res->sale_agent_user_id = 0;
        $res->travel_agent_commission_id = 0;
        $res->travel_agent_commission = 0;
        $res->sale_agent_commission = 0;
        $res->sale_agent_commission_type = null;

        if ($user->role_id == 3) { //sale_agent
            $sale_agent_user_id = $user->id;
        } else if ($user->role_id == 4) { //travel_agent
            // dd($user);
            $travel_agent_commission = TravelAgentCommission::with(['travel_agent' => ['user_obj', 'sale_agent.user_obj']])
                ->where('user_travel_agent_id', $user_id)
                ->where('journey_id', $journey->id)
                ->where('slot_id', $slot->id)
                ->where('transport_type_id', $transport_type_id)
                ->first();
            $res->travel_agent_commission_id = $travel_agent_commission->id;
            $res->travel_agent_commission_details = $travel_agent_commission;
            $res->travel_agent_user_id = $travel_agent_commission->user_travel_agent_id;
            // travel agent commission price is trip price for travel agent
            // $travel_agent_commission = $customer_collection_price - $travel_agent_commission->price;

            $res->travel_agent_commission =  $customer_collection_price - $travel_agent_commission->price;
            // $travel_agent_commission = $customer_collection_price - $journey_price->price;
            // $res->travel_agent_commission = $travel_agent_commission;
            // dd($user);
            if ($user->travel_agent->sale_agent) {
                $sale_agent_user_id = $user->travel_agent->sale_agent->user_id;
            }
        }
        if ($sale_agent_user_id) {
            $res->sale_agent_user_id = $sale_agent_user_id;
            $sale_agent_commission = $this->get_sale_agent_commission($user,$sale_agent_user_id,$journey_price,$res->travel_agent_commission, $customer_collection_price);
            $res->sale_agent_commission_type = $sale_agent_commission->commision_type;
            $res->sale_agent_commission = $sale_agent_commission->commision;
        }
        return $res;
    }

    public function update_order_commission_model($order_id){
        $order = Order::with('order_details')->find($order_id);

        foreach ($order->order_details as $order_detail_key => $order_detail) {
            
        }
    }

    public function get_sale_agent_commission($order_created_by_user ,$sale_agent_user_id,$journey_price,$travel_agent_commission, $customer_collection_price)
    {
        $sale_agent = SaleAgent::where('user_id', $sale_agent_user_id)->first();
        $res = new \stdClass();
        $res->commision_type = $sale_agent->commision_type;
        $commission = 0;
        if ($sale_agent->commision_type == 'fix_amount') {
            $commission = $sale_agent->commision;
        } 
        else if ($sale_agent->commision_type == 'sales_percent') {
            $commission = round($journey_price->price * 0.01 * $sale_agent->commision);
        } 
        else {//profit_percent
            $commission = round(($journey_price->price - $travel_agent_commission) * 0.01 * $sale_agent->commision);
        }
        if($order_created_by_user->role_id == 3){//sale_agent
            $res->commission = $commission + ($customer_collection_price - $journey_price->price);
        }
        else {//travel_agent ($order_created_by_user->role_id == 4)
            $res->commision = $commission;
        }
        return $res;
    }

}
