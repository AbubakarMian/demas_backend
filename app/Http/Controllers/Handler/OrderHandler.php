<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Journey_Slot;
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

    public function gernerate_pdf_order($order, $order_details)
    {

        $pdf = PDF::loadView('pdf.invoice', [
            'order' => $order,
            'order_details' =>  $order_details,
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

    public function get_report(Request $request)
    {
        $order_details = Order_Detail::with('driver','travel_agent','sale_agent','transport')
        ->latest()->get(); //with('order')->
        $order_details_arr = $this->admin_report_detail($request, $order_details);
        return $order_details_arr;
    }

    public function admin_report_detail(Request $request, $order_details)
    {
        $report_data = $this->default_report_detail($request, $order_details);
        return $report_data;
    }
    public function default_report_detail(Request $request, $order_details)
    {

        $report_data = [];

        foreach ($order_details as $key => $order_detail) {
            $row = [];
            $row['booking_id'] = $order_detail->sub_order_id ?? '';
            $row['booking_date'] = date('Y-m-d', $order_detail->pick_up_date_time) ?? '';
            $row['driver_name'] = $order_detail->driver->user_obj->name ?? '';
            $row['iqama_number'] = $order_detail->driver->iqama_number ?? '';
            $row['number_plate'] = $order_detail->transport->number_plate ?? '';
            $row['owner_name'] = $order_detail->transport->owner_name ?? '';
            $row['seats'] = $order_detail->transport->seats ?? '';
            $report_data[] = $row;
        }

        $report_table = [
            'booking_details' => [
                'heading' => 'Booking Detail',
                'color' => 'red',
                'columns' => [
                    [
                        'heading' => 'Booking ID',
                        'data_column' =>  'booking_id',
                        'data' => array_column($report_data, 'booking_id')
                    ],
                    [
                        'heading' => 'Booking Date',
                        'data_column' =>  'booking_date',
                        'data' => array_column($report_data, 'booking_date'),
                    ],
                ],

            ],
            'driver_details' => [
                'heading' => 'Driver Details',
                'color' => 'green',
                'columns' => [
                    [
                        'heading' => 'Driver Name',
                        'data_column' =>  'driver_name',
                        'data' => array_column($report_data, 'driver_name'),
                    ],
                    [
                        'heading' => 'Driver Iqama',
                        'data_column' =>  'iqama_number',
                        'data' => array_column($report_data, 'iqama_number'),
                    ],
                ],

            ],
            'vehicle_details' => [
                'heading' => 'Vehicle Details',
                'color' => 'blue',
                'columns' => [
                    [
                        'heading' => 'Number Plate',
                        'data_column' =>  'number_plate',
                        'data' => array_column($report_data, 'number_plate'),
                    ],
                    [
                        'heading' => 'Vehicle Owner',
                        'data_column' =>  'owner_name',
                        'data' => array_column($report_data, 'owner_name'),
                    ],
                    [
                        'heading' => 'Seating Capacity',
                        'data_column' =>  'seats',
                        'data' => array_column($report_data, 'seats'),
                    ],
                ],

            ],
        ];
        return [
            'table_info' => $report_table,
            'report_data' => $report_data,
        ];
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
