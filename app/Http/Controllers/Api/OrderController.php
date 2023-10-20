<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Models\Order;
use App\Models\Order_Detail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
    }

    public function create(Request $request)
    {
        $user = $request->attributes->get('user');
        // dd($user);
        $request_params = $request->all();
        $booking = $request_params['booking_details'];
        $commission_details = new CommissionHandler();
        $order = new Order();
        $order_pre = Order::orderbyDesc('order_id')->first();
        if (!$order_pre) {
            $order_id_uniq = 100000;
        } else {
            $order_id_uniq = $order_pre->order_id + 1;
        }
        // dd($request_params);
        $order->user_id = $user->id;
        $order->order_id = $order_id_uniq;
        $order->total_price = 0;
        $order->trip_type = $booking['type'];
        $order->cash_collected_by = null;

        $order->save();
        foreach ($booking['details'] as $detail_key => $detail) {
            $trip_price_details = $commission_details->get_trip_price_details(
                $detail['pickup_id'],
                $detail['dropoff_id'],
                $detail['transport_type_id'],
                $user->id,
                $detail['pickupdate_time']
            );
            $order_details = new Order_Detail();
            // $user_sale_agent_id = 0;
            // $user_travel_agent_id = 0;
            // if ($user->role_id == 3) { //sale agent
            //     $user_sale_agent_id = $user->id;
            // }
            // else if($user->role_id == 4){ //travel agent
            //     $user_sale_agent_id = $user->id;
            // }
            $order_details->order_id = $order->id;
            $order_details->pickup_location_id = $detail['pickup_id'];
            $order_details->drop_off_location_id = $detail['dropoff_id'];
            $order_details->transport_type_id = $detail['transport_type_id'];
            $order_details->pick_up_date_time = $detail['pickupdate_time'];
            $order_details->price = $trip_price_details->journey_price->price;
            $order_details->journey_id = $trip_price_details->journey_price->journey_id;
            $order_details->slot_id = $trip_price_details->journey_price->slot_id;
            $order_details->journey_slot_id = $trip_price_details->journey_slot->id;
            $order_details->sale_agent_user_id = $trip_price_details->commission_details->sale_agent_user_id;
            $order_details->sale_agent_commission = $trip_price_details->commission_details->sale_agent_commission;
            $order_details->sale_agent_commission_type = $trip_price_details->commission_details->sale_agent_commission_type;
            $order_details->travel_agent_user_id = $trip_price_details->commission_details->travel_agent_user_id;
            $order_details->travel_agent_commission = $trip_price_details->commission_details->travel_agent_commission;
            $order_details->status = 'pending';
            $order->price += $order_details->price;
            $order->sale_agent_user_id = $order_details->sale_agent_user_id;
            $order->sale_agent_commission_total += $order_details->sale_agent_commission;
            $order->sale_agent_commission_type = $order_details->sale_agent_commission_type;
            
            $order->travel_agent_user_id = $order_details->travel_agent_user_id;
            $order->travel_agent_commission_total += $order_details->travel_agent_commission;

            $order_details->save();
        }
        $order->save();
        return $this->sendResponse(200);
    }
}
