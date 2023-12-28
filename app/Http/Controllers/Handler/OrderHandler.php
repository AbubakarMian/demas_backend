<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Driver;
use App\Models\DriverCommission;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\SaleAgent;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PDF;

class OrderHandler
{
    use Common, APIResponse;

    public function gernerate_pdf_order($order_id)
    {

        $order = Order::with(['user_obj',
        'order_details'=>[
            'journey',
            'transport_type',
            'pickup_location',
            'dropoff_location',
            'driver_user'
        ],  
        ])->find($order_id);
        $data ['data'] = $order;
        $pdf = PDF::loadView('pdf.invoice', [
            'data' => $data,
        ]);

        // Set the paper size to A4 and the orientation to portrait
        $pdf->setPaper('a3', 'portrait');

        $path = 'invoice/' . $order->order_id . '.pdf';
        $pdfPath = public_path($path);

        // Save the PDF to the public/invoice directory
        $pdf->save($pdfPath);
        $absolute_path = asset($path);

        // Return a response with a link to the saved PDF
        return [
            'stream' => $pdf->stream($pdfPath),
            'path' => $absolute_path
        ];
        // return $pdf->stream('admin_invoice.pdf');
    }

    public function order_collect_payment($user_id,$order_obj_type,$order_id)
    {
        $user = Users::with(['sale_agent','travel_agent','driver','role'])->find($user_id);
        $order = null;
        if($order_obj_type == "order_detail"){
            $order_details = Order_Detail::with('order')->find($order_id);
            $order = $order_details->order;
            $order_details = [$order_details];
            
        }
        else{ // order
            
            $order = Order::with(['order_details' => function($query) {
                $query->where('user_payment_status', Config::get('constants.user_payment_status.pending'));
            }])->find($order_id);
            
            $order_details = $order->order_details;
        }
        foreach ($order_details as $key => $order_detail) {
            $this->collect_order_detail_payment($user,$order,$order_detail);
        }
    }

    public function collect_order_detail_payment($user_collecting_payment,$order,$order_detail){

        $order_detail->cash_collected_by = $user_collecting_payment->role->name;
        $order_detail->cash_collected_by_role = $user_collecting_payment->role_id;
        $order_detail->cash_collected_by_user_id = $user_collecting_payment->id;
        $order_detail->user_payment_status = Config::get('constants.user_payment_status.paid');
        $order_detail->is_paid = 1;

        if($user_collecting_payment->sale_agent){
            $order_detail->payable_to_admin = $order_detail->final_price - $order_detail->sale_agent_commission;
            $order_detail->sale_agent_payment_status = Config::get('constants.sales_agent.payment_status.paid');
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.pending');
            $order_detail->payment_type =  Config::get('constants.payment_type.advance_collection');

        }else if($user_collecting_payment->travel_agent){
            $order_detail->payable_to_admin = $order_detail->final_price - $order_detail->travel_agent_commission;
            $order_detail->travel_agent_payment_status = Config::get('constants.travel_agent.payment_status.paid');
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.pending');
            $order_detail->payment_type =  Config::get('constants.payment_type.advance_collection');
        }else if($user_collecting_payment->driver){// driver
            $order_detail->payable_to_admin = $order_detail->final_price - $order_detail->driver_commission;
            $order_detail->driver_payment_status = Config::get('constants.driver.payment_status.paid');
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.pending');
            $order_detail->payment_type =  Config::get('constants.payment_type.cod');

        }else{ //admin
            $order_detail->payable_to_admin = 0;
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.paid');
            $order_detail->payment_type =  Config::get('constants.payment_type.card');
        }
        $order_detail->save();
        $this->collect_admin_payment_from_wallet($user_collecting_payment,$order_detail);

    }

    function collect_admin_payment_from_wallet($user_collecting_payment,$order_detail){
        if($user_collecting_payment->wallet >=$order_detail->payable_to_admin){
            $user_collecting_payment->wallet = $user_collecting_payment->wallet - $order_detail->payable_to_admin;
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.paid');
            $user_collecting_payment->save();
        }
        else{
            $order_detail->admin_payment_status = Config::get('constants.admin_payment_status.pending');
        }
        $order_detail->save();
    }
    function update_order_detail_driver($driver_user_id,$order_detail_id){
        $order_detail = Order_Detail::with('sale_agent', 'travel_agent','order')->find($order_detail_id);
        $driver = Driver::where('user_id', $driver_user_id)->first();
        $order_detail->driver_user_id = $driver_user_id;
        
        if ($driver) {
            $order_detail->driver_commission_type = $driver->commision_type;
        } else {
            $order_detail->driver_commission_type = null;
        }
        $order_detail->save();
        $commission_handler = new CommissionHandler();
        $commission_handler->update_driver_commission($order_detail->id);
    }

    function update_order_detail_status($user_id,$order_detail_id,$status,$reason=''){
        $order_detail = Order_Detail::find($order_detail_id);
        $order = $order_detail->order;
        $order_detail->status_updated_by_user_id = $user_id;
        if($status == Config::get('constants.order_status.cancelled')){
            $order_detail->reason = $reason;
            if($status == Config::get('constants.user_payment_status.pending')){
                $order_detail->user_payment_status =  Config::get('constants.user_payment_status.cancelled');
            }
        }
        $order_detail->status = $status;
        
        $order_detail->save();
        return $order_detail;
    }

    public function get_admin_report_detail_report(Request $request)
    {
        $user = Auth::user();
        $report_details = new ReportDetails();
        $order_details_arr = [];
        if($user->role_id == 1){
            $order_details_arr = $report_details->admin_report_detail($request);
            // $order_details_arr = $report_details->sale_agent_report_detail($request);
            // $order_details_arr = $report_details->travel_agent_report_detail($request);
            
        }
        if($user->role_id == 3){
            $order_details_arr = $report_details->sale_agent_report_detail($request);
        }
        if($user->role_id == 4){
            $order_details_arr = $report_details->travel_agent_report_detail($request);
        }
        return $order_details_arr;
    }

    public function sale_agent_report_detail(Request $request)
    {
    }

    public function travel_agent_report_detail(Request $request)
    {
    }

    public function driver_report_detail(Request $request)
    {
    }
}
