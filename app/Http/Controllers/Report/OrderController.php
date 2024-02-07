<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\CommissionHandler;
use App\Http\Controllers\Handler\EmailHandler;
use App\Http\Controllers\Handler\OrderHandler;
use App\Http\Controllers\Handler\NotificationHandler;
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
use Twilio\Rest\Client;



class OrderController extends Controller
{

    public function index(Request $request)
    {
        $drivers = Users::where('role_id', 5)->pluck('name', 'id');
        $journey_list = Journey::pluck('name', 'id');
        $slot_list = Slot::pluck('name', 'id');
        $transport_type_list = Transport_Type::pluck('name', 'id');
        $travel_agent_list = Users::where('role_id', Config::get('constants.role.sale_agent'))->pluck('name', 'id');
        $drivers_list = Driver::with('user_obj')->whereHas('user_obj', function ($q) {
            $q->where('role_id', 5);
        })->orderBy('created_at', 'DESC')->get();
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
            'user_obj',
            'sale_agent_user',
            'travel_agent_user',
            'order_details' => [
                'driver.user_obj',
                'transport_type', 'journey' => ['pickup', 'dropoff']
            ],
        ]);
        $search_params = $request->all();
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
        $res = ['order_details' => $order_details];

        return $this->sendResponse(200, $res);
    }
    

    public function update_order_detail_status(Request $request, $order_detail_id)
    {
        $user = Auth::user();
        $order_handler = new OrderHandler();
        $order_detail = $order_handler->update_order_detail_status($user->id,$order_detail_id,$request->status,$request->reason);
        return $this->sendResponse(200,$order_detail);
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
        $order_handler = new OrderHandler();
        $transport_id = $request->transport_id;
        $order_detail = Order_Detail::find($order_detail_id);
        $order_detail->transport_id = $transport_id;
        $order_detail->save();
        $order_detail = $order_handler->update_order_detail_driver($request->driver_user_id,$order_detail_id);
        
        return $this->sendResponse(200, $order_detail);
    }
    public function set_manual_time(Request $request, $order_detail_id)
    {
        $order_detail = Order_Detail::find($order_detail_id);
        
        // Extract time component from the received string
        $time = date('H:i:s', strtotime($request->time));
    
        // Assuming you want to store the Unix timestamp of the extracted time
        list($hours, $minutes, $seconds) = explode(':', $time);
        $unixTime = $hours * 3600 + $minutes * 60 + $seconds;
    
        // Store the Unix timestamp
        $order_detail->manual_time_set = $unixTime;
        $order_detail->save();
        
        return $this->sendResponse(200, $order_detail);
    }
    

    
    public function send_invoice($order_id){
        // $order = Order::with('order_details', 'user_obj')->find($order_id);
        $order = Order::with([
            'user_obj',
            'sale_agent',
            'travel_agent',
            // 'order_details' => [
            'active_order_details' => [
                'driver'=>['user_obj'],
                'transport_type','transport', 'journey' => ['pickup', 'dropoff']
            ],
        ])->find($order_id);
        
        $order_handler = new OrderHandler();

        $pdf = $order_handler->gernerate_pdf_order($order_id);
        // dd($pdf );
        $receipt_url = $pdf['path'];
        $whast_app_url = $this->get_absolute_server_url_path($receipt_url);

       $this->send_url_file_whatsapp('923343722073',$whast_app_url);
       $this->send_url_file_whatsapp($order->customer_whatsapp_number,$whast_app_url);

        $email_handler = new EmailHandler();
        $email_details = [];
        $user = $order->user_obj;
        $email_details['subject'] = 'Demas Invoice';
        $email_details['attachments'][] = $receipt_url;
        $email_details['to_email'] = $user->email;
        $email_details['to_name'] = 'Abubakar';
        $email_details['data'] = [
            'user'=>$user,
            'order'=>$order,
        ];
        
        $email_details['view'] = 'pdf.order_update_email';
        $email_handler->sendEmail($email_details);
        return redirect('admin/order')->with('success', 'Invoice sent');
    }


    public function send_voucher($order_detail_id){
        // dd($order_detail_id);
        $order_detail = Order_Detail::with(['order'=>[
            'user_obj',
            'sale_agent',
            'travel_agent',

            ]])->find($order_detail_id);
        $order = $order_detail->order;
        // dd($order);
        $order_handler = new OrderHandler();

        $pdf = $order_handler->gernerate_pdf_voucher($order_detail_id);
                dd($pdf );

        $receipt_url = $pdf['path'];
        $whast_app_url = $this->get_absolute_server_url_path($receipt_url);
        // dd($whast_app_urls , $whast_app_url);

       $this->send_url_voucher_whatsapp('923343722073',$whast_app_url);
      
       $this->send_url_voucher_whatsapp($order->customer_whatsapp_number,$whast_app_url);


        $email_handler = new EmailHandler();
        $email_details = [];

        $user = $order->user_obj;
        $email_details['subject'] = 'Demas Voucher';
        $email_details['attachments'][] = $receipt_url;
        $email_details['to_email'] = $user->email;
        $email_details['to_name'] = $user->name;
        $email_details['data'] = [
            'user'=>$user,
            'order_detail'=>$order_detail,
        ];
        $email_details['view'] = 'pdf.voucher';
        $email_handler->sendEmail($email_details);
        return redirect('admin/order')->with('success', 'Invoice sent');
    }
    
    public function send_message($order_id)
    {
        // Fetch order and related details
        $order = Order::with([
            'user_obj',
            'sale_agent',
            'travel_agent',
            'active_order_details'=>[
                        'driver'=>['user_obj'],
                        'transport_type',
                        'transport',
                         'journey' => ['pickup', 'dropoff']
            ],
            'order_details'
        ])->find($order_id);
        // Create a message for WhatsApp
        //  driver , sale agent, customer, travel agent
        // $order_details = Order_deta
        $notifiction_handler = new NotificationHandler();
        foreach($order->active_order_details as $order_detail){
            if($order_detail->driver_user){
                $notifiction_handler->send_driver_info($order_detail->driver_user->whatsapp_number,$order,$order_detail);
            }
            if($order_detail->sale_agent){
                $notifiction_handler->send_driver_info($order_detail->sale_agent_user->whatsapp_number,$order,$order_detail);
            }
            if($order_detail->travel_agent){
                $notifiction_handler->send_driver_info($order_detail->travel_agent_user->whatsapp_number,$order,$order_detail);
            }
            if($order->customer_whatsapp_number){
                $notifiction_handler->send_driver_info($order->customer_whatsapp_number,$order,$order_detail);
            }

        }
        return $this->sendResponse(200);
        
    }

    
    
}
