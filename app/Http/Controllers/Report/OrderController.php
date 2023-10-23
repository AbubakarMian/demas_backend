<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Transport_Type;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $drivers = Users::where('role_id', 5)->pluck('name', 'id');
        return view('reports.order.index', compact('drivers'));
    }


    public function get_order(Request $request)
    {
        $order = Order::with(
            'user_obj',
            'sale_agent.user_obj',
            'travel_agent.user_obj',
            'driver.user_obj',
            )->get();
        $orderData['data'] = $order;
        echo json_encode($orderData);
    }

    public function get_order_details_list(Request $request, $order_id)
    {

        $order_details = Order_Detail::where('order_id', $order_id)
            ->with([
                'pickup_location',
                'dropoff_location',
                'driver.user_obj',
                'journey', // its necessary
                'journey_slot.slot', // its not necessary
            ])->get();
        // $user_drivers = Users::where('role_id',5)->get();
        $drivers = Driver::where('commision_type', 'per_trip')->with('user_obj')->get(); //Users::where('role_id',5)->get();
        $res = ['order_details' => $order_details, 'drivers' => $drivers];

        return $this->sendResponse(200, $res);
    }

    public function update_order_status(Request $request, $order_id)
    {
        $user = Auth::user();
        $order = Order::find($order_id);
        $order->status = $request->status;
        $order->status_updated_by_user_id = $user->id;
        $order->save();

        $order_details = Order_Detail::where('order_id', $order_id)->get();

        foreach ($order_details as $key => $order_detail) {
            $order_detail->status = $request->status;
            $order_detail->status_updated_by_user_id = $user->id;
            $order_detail->save();
        }
        return $this->sendResponse(200, $order);
    }

    public function update_order_detail_driver(Request $request, $order_detail_id)
    {
        $order = Order_Detail::find($order_detail_id);
        $order->driver_user_id = $request->driver_user_id;
        $order->save();
        return $this->sendResponse(200, $order);
    }
}
