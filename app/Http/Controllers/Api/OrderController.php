<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Http\Controllers\Handler\OrderHandler;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

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
                    $q->where(function($where)use($user){
                        $where->where('sale_agent_user_id', $user->id)
                        ->orWhere('user_id', $user->id);
                    });
                } elseif ($user->role_id == 4) { //sale_agent
                    $q->where(function($where)use($user){
                        $where->where('travel_agent_user_id', $user->id)
                        ->orWhere('user_id', $user->id);
                    });
                } elseif ($user->role_id == 5) { //driver
                    $q->where(function($where)use($user){
                        $where->where('driver_user_id', $user->id)
                        ->orWhere('user_id', $user->id);
                    });
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
        $commission_handler = new CommissionHandler();
        $order = new Order();
        $order_pre = Order::where('order_id','>',99999)->orderbyDesc('order_id')->first();
        if (!$order_pre) {
            $order_id_uniq = 100000;
        } else {
            $order_id_uniq = $order_pre->order_id + 1;
        }
        $order->user_id = $user->id;
        $order->order_created_by_role_id = $user->role_id;
        $order->order_id = $order_id_uniq;
        $order->discount = 0;
        if(isset($booking['customer_name'])){
            $order->customer_name = $booking['customer_name'];
        }
        else{
            $order->customer_name = $user->name;
        }
        if(isset($booking['customer_phone_number'])){
            $order->customer_number = $booking['customer_phone_number'];
        }
        else{
            $order->customer_number = $user->phone_no;
        }
        if(isset($booking['customer_whatsapp_number'])){
            $order->customer_whatsapp_number = $booking['customer_whatsapp_number'];
        }
        else{
            $order->customer_whatsapp_number = $user->whatsapp_number;
        }
        
        if(isset($booking['show_price_in_user_invoice'])){
            $order->show_price_in_user_invoice = $booking['show_price_in_user_invoice'];
        }
        else{
            $order->customer_whatsapp_number = $user->whatsapp_number;
        }
        $order->travel_agent_user_id = $booking['travel_agent_user_id'] ?? 0;
        $order->customer_collection_price = 0;
        $order->discounted_price = 0;
        $order->actual_price = 0;
        $order->final_price = 0;
        $order->trip_type = $booking['type'];
        // $order->payment_type = Config::get('constants.payment_type.cod');
        // $order->cash_collected_by = null;
        $order->status = 'pending';
        $order->save();
        $detail_count = 0;
        foreach ($booking['details'] as $detail_key => $detail) {
            $detail_count++;
            $order_details = new Order_Detail();
            $order_details->order_id = $order->id;
            $order_details->sub_order_id = $order_id_uniq.'-'.($detail_key+1);
            $order_details->pickup_location_id = $detail['pickup_id'];
            $order_details->drop_off_location_id = $detail['dropoff_id'];
            $order_details->transport_type_id = $detail['transport_type_id'];
            $order_details->pick_up_date_time = $detail['pickupdate_time'];
            $order_details->customer_collection_price = $detail['customer_collection_price'];
            // $order_details->travel_agent_user_id = $order->travel_agent_user_id; // if sale agent is select a travel agent from its drop down
            $order->customer_collection_price += $order_details->customer_collection_price;
            $order_details->journey_id = $commission_handler->get_journey($detail['pickup_id'], $detail['dropoff_id'])->id;
            $order_details->slot_id = $commission_handler->get_slot($detail['pickupdate_time'])->id;
            $order_details->journey_slot_id = $commission_handler->get_journey_slot($order_details->journey_id, $order_details->slot_id)->id;
            $order_details->status = Config::get('constants.order_status.pending');
            $order_details->payment_type = Config::get('constants.payment_type.cod');
            $order_details->user_payment_status = Config::get('constants.user_payment_status.pending');
            $order_details->driver_payment_status = Config::get('constants.driver.payment_status.paid');
            $order_details->save();
        }
        $order->save();
        // $order = Order::with(['order_details','user_obj',
        // 'travel_agent',
        // 'sale_agent',
        // 'travel_agent_user','sale_agent_user'])->find($order->id);
        
        $commission_handler->set_order_ref_agents($order->id);
        $commission_handler->update_trip_prices($order->id);
        $commission_handler->update_commissions_prices($order->id);

        // generate pdf 
        $order_handler = new OrderHandler();
        $pdf = $order_handler->gernerate_pdf_order($order->id);

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
                    'transport_type', 'journey' => ['pickup', 'dropoff']
                ]
            ])
            ->where('id', $order_id)->first();
        return $this->sendResponse(200, $order);
    }

    public function order_collect_payment(Request $request, $order_id)
    {
        $user = $request->attributes->get('user');
        // $order = Order:: find($order_id);
        // $order->is_paid = true;
        // $order->cash_collected_by = 'driver';
        // $order->cash_collected_by_user_id = $user->id;
        $order_handler = new OrderHandler();
        $order_handler->order_collect_payment($user->id,$request->order_type,$order_id);
        return $this->sendResponse(200, [
            'order_id',$order_id,
            'request',$request->all()
        ]);
    }

    public function cancel_request($order_id){
        $order = Order_Detail::find($order_id);
        $order->status = Config::get('constants.order_status.cancelled');
        // $order->cancelled = Config::get('constants.user_payment_status.cancelled');
        $order->save();
        return $this->sendResponse(200,$order );
    }
}
