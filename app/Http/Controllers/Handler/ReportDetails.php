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

class ReportDetails
{
    use Common, APIResponse;


    public function admin_report_detail(Request $request, $order_details)
    {
        $report_data = [];
        $row = [];
        $table_info = [];

        foreach ($order_details as $key => $order_detail) {
            // $row = $this->default_report_detail($order_detail);
            $row = $this->admin_agent_report_detail($order_detail, $row);
            $row = $this->admin_margin_calculation_report_detail($order_detail, $row);
            $report_data[] = $row;
        }
        // $table_info = $this->default_report_detail_info();
        $table_info = $this->admin_agent_report_detail_info($table_info);
        $table_info = $this->admin_margin_calculation_report_detail_info($table_info);

        return [
            'table_info' => $table_info,
            'report_data' => $report_data,
        ];
    }
    public function admin_margin_calculation_report_detail($order_detail, $row)
    {
        $office_profit = $order_detail->customer_collection_price - 
        $order_detail->travel_agent_commission-$order_detail->sale_agent_commission-$order_detail->driver_commission;
        $row['admin_margin_calculation_booking_rate'] = $order_detail->customer_collection_price; // match with db
        $row['admin_margin_calculation_travel_agent'] = $order_detail->travel_agent_commission;
        $row['admin_margin_calculation_sales_agent'] = $order_detail->sale_agent_commission;
        $row['admin_margin_calculation_hire_rate'] = $order_detail->driver_commission;
        $row['admin_margin_calculation_office_profit'] = $office_profit;
        return $row;
    }
    public function admin_agent_report_detail($order_detail, $row)
    {
        $row['admin_agent_direct_customer'] = $order_detail->order->user_obj->role_id == 2 ?
            $order_detail->order->user_obj->name : '';
        $row['admin_agent_travel_agent'] = $order_detail->travel_agent_user->name ?? '';
        $row['admin_agent_sales_agent'] = $order_detail->sale_agent_user->name ?? '';
        $row['admin_agent_service_type'] = $order_detail->journey->name ?? '';
        return $row;
    }
    public function default_report_detail($order_detail)
    {
        $row = [];
        $row['booking_id'] = $order_detail->sub_order_id ?? '';
        $row['booking_date'] = date('Y-m-d', $order_detail->pick_up_date_time) ?? '';
        $row['driver_name'] = $order_detail->driver_user->name ?? '';
        $row['iqama_number'] = $order_detail->driver->iqama_number ?? '';
        $row['number_plate'] = $order_detail->transport->number_plate ?? '';
        $row['owner_name'] = $order_detail->transport->owner_name ?? '';
        $row['vehicle_type'] = $order_detail->transport->transport_type->name ?? '';
        $row['seats'] = $order_detail->transport->seats ?? '';
        return $row;
    }
    public function admin_margin_calculation_report_detail_info($table_info)
    {
        $table_info['margin_calculation'] = [
            'heading' => 'Margin Calculation',
            'color' => 'rgb(248 203 173)',
            'columns' => [
                [
                    'heading' => 'Booking Rate',
                    'data_column' =>  'admin_margin_calculation_booking_rate', //match with pre function
                ],
                [
                    'heading' => 'Travel Agent Margin',
                    'data_column' =>  'admin_margin_calculation_travel_agent',
                ],
                [
                    'heading' => 'Sales Margin',
                    'data_column' =>  'admin_margin_calculation_sales_agent',
                ],
                [
                    'heading' => 'Hired Rates',
                    'data_column' =>  'admin_margin_calculation_hire_rate',
                ],
                [
                    'heading' => 'Office Profit',
                    'data_column' =>  'admin_margin_calculation_office_profit',
                ],
            ],
        ];
        return $table_info;
    }
    public function admin_agent_report_detail_info($table_info)
    {
        $table_info['admin_agent_details'] = [
            'heading' => 'Agent Details',
            'color' => 'rgb(248 203 173)',
            'columns' => [
                [
                    'heading' => 'Direct Customer',
                    'data_column' =>  'admin_agent_direct_customer',
                ],
                [
                    'heading' => 'Travel Agent',
                    'data_column' =>  'admin_agent_travel_agent',
                ],
                [
                    'heading' => 'Sales Agent',
                    'data_column' =>  'admin_agent_sales_agent',
                ],
                [
                    'heading' => 'Service Type',
                    'data_column' =>  'admin_agent_service_type',
                ],
            ],
        ];
        return $table_info;
    }
    public function default_report_detail_info()
    {
        $table_info = [
            'booking_details' => [
                'heading' => 'Booking Detail',
                'color' => 'rgb(255 230 153)',
                'columns' => [
                    [
                        'heading' => 'Booking ID',
                        'data_column' =>  'booking_id',
                    ],
                    [
                        'heading' => 'Booking Date',
                        'data_column' =>  'booking_date',
                    ],
                ],

            ],
            'driver_details' => [
                'heading' => 'Driver Details',
                'color' => 'rgb(248 203 173)',
                'columns' => [
                    [
                        'heading' => 'Driver Name',
                        'data_column' =>  'driver_name',
                    ],
                    [
                        'heading' => 'Driver Iqama',
                        'data_column' =>  'iqama_number',
                    ],
                ],

            ],
            'vehicle_details' => [
                'heading' => 'Vehicle Details',
                'color' => 'rgb(255 230 153)',
                'columns' => [
                    [
                        'heading' => 'Number Plate',
                        'data_column' =>  'number_plate',
                    ],
                    [
                        'heading' => 'Vehicle Owner',
                        'data_column' =>  'owner_name',
                    ],
                    [
                        'heading' => 'Vehicle Type',
                        'data_column' =>  'vehicle_type',
                    ],
                    [
                        'heading' => 'Seating Capacity',
                        'data_column' =>  'seats',
                    ],
                ],

            ],
        ];
        return $table_info;
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
