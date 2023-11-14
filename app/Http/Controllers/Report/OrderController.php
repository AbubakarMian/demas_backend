<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Http\Controllers\Handler\EmailHandler;
use App\Http\Controllers\Handler\OrderHandler;
use App\Models\Driver;
use App\Models\DriverCommission;
use App\Models\Order;
use App\Models\Order_Detail;
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
        return view('reports.order.index', compact('drivers'));
    }


    public function get_order(Request $request)
    {
        $order = Order::with([
            'user_obj',
            'sale_agent.user_obj',
            'travel_agent.user_obj',
            'order_details' => [
                'driver.user_obj',
                'transport_type', 'journey' => ['pickup', 'dropoff']
            ],
        ])->get();
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
        $driver_user_id = $request->driver_user_id;
        $order_detail = Order_Detail::with('sale_agent','order')->find($order_detail_id);
        $driver = Driver::where('driver_user_id', $driver_user_id)->first();
        $sale_agent = $order_detail->sale_agent;
        $order_detail->driver_user_id = $driver_user_id;
        $order_detail->driver_commission_type = $driver->commision_type;
        $order_detail->save();

        if (
            $sale_agent && $sale_agent->commision_type ==
            Config::get('constants.commission_types.profit_percent')
            && $order_detail->driver->commision_type == Config::get('constants.driver.commission_types.per_trip')
        ) {
            // get commission
            // $commission = round(($journey_price->price - $travel_agent_commission) * 0.01 * $sale_agent->commision);

            $driver_commission = DriverCommission::where()
                ->where('user_driver_id', $driver_user_id)
                ->where('journey_id', $order_detail->journey_id)
                ->where('slot_id', $order_detail->slot_id)
                ->where('transport_type_id', $order_detail->transport_type_id)
                ->first();
            if ($driver_commission) {
                $profit = $order_detail->final_price - $order_detail->sale_agent_commission
                    - $order_detail->travel_agent_commission - $driver_commission->commission;
                $commission = round($profit  * 0.01 * $sale_agent->commision);
                $order_detail->sale_agent_commission = $commission;
                $order_detail->driver_commission = $driver_commission->commission;
                $order_detail->save();
            }
        }
        // $order_detail->save();
        // $order = $order_detail->order;
        $commission_handler = new CommissionHandler();
        $commission_handler->update_order_commission_model($order_detail->order_id);
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
