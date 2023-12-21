<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\DriverCommission;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Order;
use App\Models\Order_Detail;
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

class CommissionHandler
{
    use Common, APIResponse;

    public function update_commissions_prices($order) // order with order_details
    {
        $this->update_travel_agent_commission($order);
        $this->update_sale_agent_commission($order,$order->order_details);
    }

    public function update_travel_agent_commission($order)
    {
        if ($order->travel_agent) { //travel_agent
            foreach ($order->order_details as $key => $order_details) {
                // $order_details->travel_agent_user_id = $order->user_id;
                $order_details->travel_agent_commission = $order_details->customer_collection_price - $order_details->final_price;
                $order->travel_agent_commission_total += $order_details->travel_agent_commission;
                // if ($order->travel_agent->sale_agent) {
                    // $order_details->sale_agent_user_id = $order->travel_agent->sale_agent->user_id;
                    // $order->sale_agent_user_id = $order_details->sale_agent_user_id;
                // }
                $order_details->save();
                $order->save();
            }
        }
    }

    public function update_driver_commission($order_detail_id){
        $order_detail = Order_Detail::with( 'order', 'driver')->find($order_detail_id);
        $driver = $order_detail;

        if ($driver && $driver->commision_type == Config::get('constants.driver.commission_types.per_trip')) {

            $driver_commission = DriverCommission::where()
                ->where('user_driver_id', $order_detail->driver_user_id)
                ->where('journey_id', $order_detail->journey_id)
                ->where('slot_id', $order_detail->slot_id)
                ->where('transport_type_id', $order_detail->transport_type_id)
                ->first();
            if ($driver_commission) {
                $order_detail->driver_commission = $driver_commission->commission;
                $order_detail->save();
            }
        }
        $commission_handler = new CommissionHandler();
        $commission_handler->update_sale_agent_commission($order_detail->order,[$order_detail]);

    }

    public function update_sale_agent_commission($order,$order_details_arr)
    {
        $sale_agent = $order->sale_agent;
        $extra_commission = 0;
        if ($sale_agent) {
            $sale_agent_commission_types = Config::get('constants.sales_agent.commission_types');
            foreach ($order_details_arr as $key => $order_details) {
                $commission = 0;
                if ($sale_agent->commision_type == $sale_agent_commission_types['fix_amount']) {
                    $commission = $sale_agent->commision;
                } else if ($sale_agent->commision_type == $sale_agent_commission_types['sales_percent']) {
                    $commission = round($order_details->final_price * 0.01 * $sale_agent->commision);
                } else { //profit_percent
                    $commission = round(($order_details->final_price - $order_details->travel_agent_commission - $order_details->driver_commission)
                        * 0.01 * $sale_agent->commision);
                }
                if($order->user_obj->sale_agent){
                    $extra_commission = ($order_details['customer_collection_price'] -  $order_details->final_price);
                }
                $order_details->sale_agent_commission = $commission + $extra_commission;
                $order->sale_agent_commission_total += $order_details->sale_agent_commission;
                $order_details->sale_agent_commission_type = $sale_agent->commision_type;
                $order->sale_agent_commission_type = $sale_agent->commision_type;
                $order_details->save();
                $order->save();
            }
        }
    }


    public function update_trip_prices($order)
    {
        foreach ($order->order_details as $order_detail_count => $order_details) {
            $order_detail_count++;
            $journey_price = $this->journey_price($order, $order_details);
            $actual_price = $journey_price->price;

            $order_details->actual_price = $actual_price;
            $order->actual_price += $actual_price;

            if ($order->order_created_by_role_id == 2 && $order_detail_count % 3 == 0) {
                $discount_percent_obj = Settings::where('name', Config::get('constants.settings.discount'))
                    ->first();
                $discount_percent = $discount_percent_obj->value;
                $order_details->discount = $actual_price * $discount_percent * 0.01;
                $order_details->discounted_price = $actual_price - $order_details->discount;
                $order_details->final_price = $order_details->discounted_price;
                $order->discount += $order_details->discount;
                $order->discounted_price += $order_details->discounted_price;
                $order->final_price += $order->discounted_price;
            } else {
                $order_details->final_price += $actual_price;
                $order->final_price += $order_details->final_price;
            }
            $order_details->save();
            $order->save();
        }
    }

    public function set_order_ref_agents($order)
    {
        foreach ($order->order_details as $key => $order_details) {
            if ($order->travel_agent) { //travel agent
                $order_details->travel_agent_user_id = $order->user_obj->id;
                $order->travel_agent_user_id = $order_details->travel_agent_user_id;
                if ($order->travel_agent->sale_agent) {
                    $order_details->sale_agent_user_id = $order->user_obj->travel_agent->sale_agent->user_id;
                    $order->sale_agent_user_id = $order_details->sale_agent_user_id;
                }
            } else if ($order->sale_agent) {
                $order_details->sale_agent_user_id = $order->user_obj->id;
                $order_details->sale_agent_user_id = $order->user_obj->id;
                $order->sale_agent_user_id = $order_details->sale_agent_user_id;
                $order_details->travel_agent_user_id = $order->travel_agent_user_id;
            }
            $order_details->save();
        }
        $order->save();
    }

    public function journey_price($order, $order_detail)
    {
        $journey_price = $this->get_journey_price_db($order->user_id,$order_detail->journey_id,
                                $order_detail->slot_id,$order_detail->transport_type_id)->first();
        return $journey_price;
    }

    public function get_journey_price_db($order_created_by_user_id,$journey_id,$slot_id,$transport_type_id=0){
        $user = Users::with(['sale_agent', 'travel_agent',
        ])->find($order_created_by_user_id);

        $where = [
            'journey_id'=>$journey_id,
            'slot_id'=>$slot_id,
        ];
        if($transport_type_id){
            $where['transport_type_id'] = $transport_type_id;
        }
        if($user->travel_agent){
            $where['user_travel_agent_id'] = $order_created_by_user_id;
            $journey_price = TravelAgentCommission::where($where);
        }
        elseif($user->sale_agent){
            $where['user_sale_agent_id'] = $order_created_by_user_id;
            $journey_price = SalesAgentTripPrice::where($where);
        }
        else{
            $journey_price = TransportPrices::where($where);
        }
        return $journey_price;
    }

    public function get_journey_slot($journey_id, $slot_id)
    {
        $journey_slot = Journey_Slot::where('journey_id', $journey_id)
            ->where('slot_id', $slot_id)
            ->first();
        return $journey_slot;
    }

    public function get_journey_price($journey_id, $slot_id, $transport_type_id, $user)
    {

        if ($user->travel_agent) {
            $user_id = $user->id;
            $journey_price = TravelAgentCommission::with(['travel_agent' => ['user_obj', 'sale_agent.user_obj']])
                ->where('user_travel_agent_id', $user_id)
                ->where('journey_id', $journey_id)
                ->where('slot_id', $slot_id)
                ->where('transport_type_id', $transport_type_id)
                ->first();
        } else {
            $journey_price = TransportPrices::where('transport_type_id', $transport_type_id)
                ->where('journey_id', $journey_id)
                ->where('slot_id', $slot_id)
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

    public function get_car_commission_details($slot, $journey, $journey_price, $transport_type_id, $user, $customer_collection_price)
    {
        // $user = Users::with(['sale_agent','travel_agent','driver'])->find($user_id);
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
            $travel_agent_commission = TravelAgentCommission::with(['travel_agent' => ['user_obj', 'sale_agent.user_obj']])
                ->where('user_travel_agent_id', $user->id)
                ->where('journey_id', $journey->id)
                ->where('slot_id', $slot->id)
                ->where('transport_type_id', $transport_type_id)
                ->first();
            $res->travel_agent_commission_id = $travel_agent_commission->id;
            $res->travel_agent_commission_details = $travel_agent_commission;
            $res->travel_agent_user_id = $travel_agent_commission->user_travel_agent_id;
            // travel agent commission price is trip price for travel agent
            $res->travel_agent_commission =  $customer_collection_price - $journey_price->price;
            // $res->travel_agent_commission =  $customer_collection_price - $travel_agent_commission->price;

            if ($user->travel_agent->sale_agent) {
                $sale_agent_user_id = $user->travel_agent->sale_agent->user_id;
            }
        }
        if ($sale_agent_user_id) {
            $res->sale_agent_user_id = $sale_agent_user_id;
            $sale_agent_commission = $this->get_sale_agent_commission($user, $sale_agent_user_id, $journey_price, $res->travel_agent_commission, $customer_collection_price);
            $res->sale_agent_commission_type = $sale_agent_commission->commision_type;
            $res->sale_agent_commission = $sale_agent_commission->commision;
        }
        return $res;
    }

    public function get_sale_agent_commission($order_created_by_user, $sale_agent_user_id, $journey_price, $travel_agent_commission, $customer_collection_price)
    {
        $sale_agent = SaleAgent::where('user_id', $sale_agent_user_id)->first();
        $res = new \stdClass();
        $res->commision_type = $sale_agent->commision_type;
        $commission = 0;
        if ($sale_agent->commision_type == 'fix_amount') {
            $commission = $sale_agent->commision;
        } else if ($sale_agent->commision_type == 'sales_percent') {
            $commission = round($journey_price->price * 0.01 * $sale_agent->commision);
        } else { //profit_percent
            $commission = round(($journey_price->price - $travel_agent_commission) * 0.01 * $sale_agent->commision);
        }
        if ($order_created_by_user->role_id == 3) { //sale_agent
            $res->commission = $commission + ($customer_collection_price - $journey_price->price);
        } else { //travel_agent ($order_created_by_user->role_id == 4) travel_agent
            $res->commision = $commission;
        }
        return $res;
    }
}
