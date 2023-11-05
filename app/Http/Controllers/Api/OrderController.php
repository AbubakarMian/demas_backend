<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Http\Controllers\Handler\OrderHandler;
use App\Models\Order;
use App\Models\Order_Detail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->attributes->get('user');
        $orders = Order:: //get();
            with([
                'user_obj', 'sale_agent', 'travel_agent',
                'order_details' => [
                    'driver.user_obj',
                    'transport_type', 'journey' => ['pickup', 'dropoff']
                ]
            ]);

        if ($user->role_id == 2) { // user
            $orders = $orders->where('user_id', $user->id);
        } else {
            $orders = $orders->whereHas('order_details', function ($q) use ($user) {
                if ($user->role_id == 3) { //sale_agent
                    $q->where('sale_agent_user_id', $user->id);
                } elseif ($user->role_id == 4) { //sale_agent
                    $q->where('travel_agent_user_id', $user->id);
                } elseif ($user->role_id == 5) { //driver
                    $q->where('driver_user_id', $user->id);
                }
            });
        }
        $orders = $orders->latest()->get();
        return $this->sendResponse(200, $orders);
    }

    public function create(Request $request)
    {
        $user = $request->attributes->get('user');

        $request_params = $request->all();
        $booking = $request_params['booking_details'];
        $commission_details = new CommissionHandler();
        $order = new Order();
        $order_pre = Order::where('order_id','>',99999)->orderbyDesc('order_id')->first();
        if (!$order_pre) {
            $order_id_uniq = 100000;
        } else {
            $order_id_uniq = $order_pre->order_id + 1;
        }
        
        $order->user_id = $user->id;
        $order->order_id = $order_id_uniq;
        $order->total_price = 0;
        $order->trip_type = $booking['type'];
        $order->cash_collected_by = null;
        $order->status = 'pending';

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
            $order_details->order_id = $order->id;
            $order_details->sub_order_id = $order_id_uniq.'-'.($detail_key+1);
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

        $order_obj = Order::with('order_details')->find($order->id);
        // generate pdf 
        $order_handler = new OrderHandler();
        $pdf = $order_handler->gernerate_pdf_order($order_obj,$order_obj->order_details);

        // create pdf of order invoice save in invoice url
        $order->receipt_url = $pdf['path'];
        $order->save();

        // return $pdf['stream'];

        // $user->email send email with attachment

        return $this->sendResponse(200, $order);
    }

    public function detail(Request $request, $order_id)
    {

        $user = $request->attributes->get('user');
        $order = Order:: //get();
            with([
                'user_obj', 'sale_agent', 'travel_agent',
                'order_details' => [
                    'driver.user_obj',
                    'transport_type', 'journey'
                ]
            ])
            ->where('id', $order_id)->first();
        return $this->sendResponse(200, $order);
    }

    public function order_pay(Request $request, $order_id)
    {
        $user = $request->attributes->get('user');
        $order = Order:: find($order_id);
        $order->is_paid = true;
        $order->cash_collected_by = 'driver';
        $order->cash_collected_by_user_id = $user->id;
        return $this->sendResponse(200, $order);
    }
}
