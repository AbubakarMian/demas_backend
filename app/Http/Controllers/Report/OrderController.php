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



    // public function create()
    // {
    //     $control = 'create';
    //     $transport_type = Transport_Type::pluck('name', 'id');
    //     return view('admin.order.create', compact('control', 'transport_type'));
    // }

    // public function save(Request $request)
    // {
    //     $order = new order();
    //     return $this->add_or_update($request, $order);

    //     return redirect('admin/order');
    // }
    // public function edit($id)
    // {
    //     $control = 'edit';
    //     $order = Order_Detail::find($id);
    //     // dd($order->images);
    //     $transport_type = Transport_Type::pluck('name', 'id');
    //     return view(
    //         'admin.order.create',
    //         compact(
    //             'control',
    //             'order',
    //             'transport_type',

    //         )
    //     );
    // }

    // public function update(Request $request, $id)
    // {
    //     $order = Order_Detail::find($id);
    //     // Order_Detail::delete()
    //     return $this->add_or_update($request, $order);
    //     return Redirect('admin/order');
    // }


    // public function add_or_update(Request $request, $order)
    // {
    //     // dd(json_encode($request->order_images_upload));
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'order_images_upload' => 'required'
    //         ],
    //         [
    //             'order_images_upload' => [
    //                 'required' => 'Images Required'
    //             ]
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return back()->with('error', $validator->messages());
    //     }
    //     $order->transport_type_id = $request->transport_type_id;
    //     $order->name = $request->name;
    //     $order->details = $request->details;
    //     $order->images = $request->order_images_upload;
    //     $order->features = $request->features;
    //     $order->seats = $request->seats;
    //     $order->luggage = $request->luggage;
    //     $order->doors = $request->doors;
    //     $order->booking = $request->booking;
    //     $order->dontforget = $request->dontforget;
    //     $order->images = $request->order_images_upload;
    //     $order->save();
    //     return Redirect('admin/order');
    // }

    // public function destroy_undestroy($id)
    // {
    //     $order = Order_Detail::find($id);
    //     if ($order) {
    //         Order_Detail::destroy($id);
    //         $new_value = 'Activate';
    //     } else {
    //         Order_Detail::withTrashed()->find($id)->restore();
    //         $new_value = 'Delete';
    //     }
    //     $response = Response::json([
    //         "status" => true,
    //         'action' => Config::get('constants.ajax_action.delete'),
    //         'new_value' => $new_value
    //     ]);
    //     return $response;
    // }
}
