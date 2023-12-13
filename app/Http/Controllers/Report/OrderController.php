<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Http\Controllers\Handler\EmailHandler;
use App\Http\Controllers\Handler\OrderHandler;
use App\Models\Driver;
use App\Models\DriverCommission;
use App\Models\Journey;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\Transport_Type;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PDF;


class OrderController extends Controller
{

    public function index(Request $request)
    {
        $drivers = Users::where('role_id', 5)->pluck('name', 'id');
        $journey_list = Journey::pluck('name', 'id');
        $slot_list = Slot::pluck('name', 'id');
        $transport_type_list = Transport_Type::pluck('name', 'id');
        $travel_agent_list = Users::where('role_id', Config::get('constants.role.sale_agent'))->pluck('name', 'id');
        $drivers_list = Driver::with('user_obj')->get();
        $transport_list = Transport::with('transport_type:id,name')->get();

        $drivers_list->transform(function ($driver) {
            $item = new \stdClass();
            $item->user_obj_name = $driver->user_obj->name;
            $item->driver_user_id = $driver->user_obj->id;
            $item->id = $driver->id;
            $item->driver_category = $driver->driver_category;
            return $item;
        });

        $transport_list->transform(function ($transport) {
            $item = new \stdClass();
            $item->transport_type_name = $transport->transport_type->name;
            $item->id = $transport->id;
            $item->name = $transport->name;
            return $item;
        });

        // $drivers_list = Users::where('role_id', Config::get('constants.role.driver'))->pluck('name', 'id');
        return view(
            'reports.order.index',
            compact(
                'drivers',
                'journey_list',
                'slot_list',
                'transport_type_list',
                'travel_agent_list',
                'drivers_list',
                'transport_list',
            )
        );
    }

    public function get_order(Request $request)
    {
        $order = Order::with([
            'user_obj:name,role_id',
            'sale_agent.user_obj:name,role_id',
            'travel_agent.user_obj:name,role_id',
            'order_details' => [
                'driver.user_obj:name,role_id',
                'transport_type', 'journey' => ['pickup', 'dropoff']
            ],
        ]);
        $search_params = $request->all();
        // if($request->journey_id){
        $order = $order->whereHas(
            'order_details',
            function ($q) use ($search_params) {
                if (isset($search_params['journey_id'])) {
                    $q->where('journey_id', $search_params['journey_id']);
                }
                if (isset($search_params['slot_id'])) {
                    $q->where('slot_id', $search_params['slot_id']);
                }
                if (isset($search_params['transport_type_id'])) {
                    $q->where('transport_type_id', $search_params['transport_type_id']);
                }
                if (isset($search_params['travel_agent_user_id'])) {
                    $q->where('travel_agent_user_id', $search_params['travel_agent_user_id']);
                }
            }
        );
        // }
        $orderData['data'] = $order->latest()->get();
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
                'transport_type'
            ])->get();
            // dd($order_details);
        // $user_drivers = Users::where('role_id',5)->get();
        // $drivers = Driver::where('commision_type', 'per_trip')->with('user_obj')->get(); //Users::where('role_id',5)->get();
        $res = ['order_details' => $order_details];

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
        $driver_user_id = $request->driver_user_id;
        $transport_id = $request->transport_id;
        $order_detail = Order_Detail::with('sale_agent', 'order')->find($order_detail_id);
        $driver = Driver::where('user_id', $driver_user_id)->first();
        // $sale_agent = $order_detail->sale_agent;
        $order_detail->driver_user_id = $driver_user_id;
        $order_detail->transport_id = $transport_id;
        if ($driver) {
            $order_detail->driver_commission_type = $driver->commision_type;
        } else {
            $order_detail->driver_commission_type = null;
        }
        $order_detail->save();

        $order_detail = Order_Detail::with( 'order', 'driver')->find($order_detail_id);

        if ($order_detail->driver && $order_detail->driver->commision_type == Config::get('constants.driver.commission_types.per_trip')) {

            $driver_commission = DriverCommission::where()
                ->where('user_driver_id', $driver_user_id)
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
        $order = Order::with([
            'order_details', 'user_obj',
            'travel_agent',
            'sale_agent',
            'travel_agent_user', 'sale_agent_user'
        ])->find($order_detail->order_id);
        $commission_handler->update_commissions_prices($order);
        return $this->sendResponse(200, $order_detail);
    }
    public function send_invoice($order_id)
    {
        $order = Order::with('order_details', 'user_obj')->find($order_id);
        $order_handler = new OrderHandler();
        $pdf = $order_handler->gernerate_pdf_order($order, $order->order_details);

        // create pdf of order invoice save in invoice url
        $receipt_url = $pdf['path'];

        $email_handler = new EmailHandler();
        $email_details = [];
        // $email_details['cc'] = [];
        // $email_details['cc'][] = [
        //     'from_email' => 'saadyasirthegreat@gmailcom',
        //     'from_name' => 'Saad cc',
        // ];

        $user = $order->user_obj;
        $email_details['bcc'][] = [
            'from_email' => 'abubakarhere90@gmailcom',
            'from_name' => 'Abubakar here bcc',
        ];
        $email_details['subject'] = 'Demas Invoice';
        $email_details['attachments'][] = $receipt_url;
        // $email_details['to_email'] = 'abubakrmianmamoon@gmail.com';
        $email_details['to_email'] = $user->email;
        $email_details['to_name'] = 'Abubakar';
        $email_details['data'] = $user;
        $email_details['view'] = 'pdf.invoice';
        $email_handler->sendEmail($email_details);
        return redirect()->back()->with('success', 'Invoice sent');
    }
}
