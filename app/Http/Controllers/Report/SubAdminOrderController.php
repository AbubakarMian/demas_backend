<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Transport_Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubAdminOrderController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $user = User::find(10);;
        // dd($user);
        return view('reports.sub_admin.order.index', compact('user'));
    }
    public function get_drivers_order(Request $request)
    {
        $user = Auth::user();
        $user = User::find(10);;

        $order_details = Order_Detail::with(
            ['order' => ['user_obj', 'sale_agent.user_obj', 'travel_agent.user_obj'],
            'pickup_location',
            'dropoff_location',
            'journey',
            'driver.user_obj',
            'journey_slot',
            ])
        ->where('driver_user_id', $user->id);
// dd($order_details->get());
        $order_details = $order_details->orderBy('created_at', 'DESC')->select('*')->get();
        $orderData['data'] = $order_details;
        $orderData['role_id'] = $user->role_id;
        echo json_encode($orderData);
    }
    public function get_order(Request $request)
    {
        $user = Auth::user();
        $order = Order::with('user_obj', 'sale_agent.user_obj', 'travel_agent.user_obj');
        if ($user->role_id == 3) { // 3 = sale_agent 
            $order = $order->where('user_sale_agent_id', $user->id);
        } elseif ($user->role_id == 4) { // 4 = travel_agent 
            $order = $order->where('user_travel_agent_id', $user->id);
        } 
        $order = $order->orderBy('created_at', 'DESC')->select('*')->get();
        $orderData['data'] = $order;
        $orderData['role_id'] = $user->role_id;
        echo json_encode($orderData);
    }

    public function get_order_details_list(Request $request, $order_id)
    {
        $user = Auth::user();
        $order_details = Order_Detail::where('order_id', $order_id)
            ->with([
                'pickup_location',
                'dropoff_location',
                'driver.user_obj',
                'journey', // its necessary
                'journey_slot.slot', // its not necessary
            ])->get();
        $orderData['role_id'] = $user->role_id;
        $orderData['order_details'] = $order_details;

        return $this->sendResponse(200, $orderData);
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
