<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
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
        $pdf = PDF::loadView('pdf.invoice', [
            'order' => $order,
        ]);

        // Set the paper size to A4 and the orientation to portrait
        $pdf->setPaper('a4', 'portrait');

        $path = 'invoice/' . $order->order_id . 'pdf';
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

    // public function pdf_maker()
    // {
    //     $pdf = PDF::loadView('pdf.invoice', [
    //         'title' => 'CodeAndDeploy.com Laravel Pdf Tutorial',
    //         'description' => 'This is an example Laravel pdf tutorial.',
    //         'footer' => 'by <a href="https://codeanddeploy.com">codeanddeploy.com</a'
    //     ]);
    
    //     // Set the paper size to A4 and the orientation to portrait
    //     $pdf->setPaper('a4', 'portrait');
    
    //     $pdfPath = public_path('invoice/admin_invoice.pdf');
    
    //     // Save the PDF to the public/invoice directory
    //     $pdf->save($pdfPath);
    
    //     // Return a response with a link to the saved PDF
    //     return $pdf->stream('admin_invoice.pdf');
    // }

    public function get_admin_report_detail_report(Request $request)
    {
        $report_details = new ReportDetails();
        $order_details_arr = $report_details->admin_report_detail($request);
        // $order_details_arr = $report_details->travel_agent_report_detail($request);
        // $order_details_arr = $report_details->sale_agent_report_detail($request);
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
