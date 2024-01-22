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
use Illuminate\Support\Facades\Config;
use PDF;

class ReportDetails
{
    use Common, APIResponse;


    public function admin_report_detail(Request $request)
    {
        $order_details = Order_Detail::with(
            'order',
            'driver',
            'driver_user',
            'travel_agent_user',
            'sale_agent_user',
            'journey',
            'transport.transport_type'
                 )
            ->latest()->get();

        $report_data = [];
        $row = [];
        $table_info = [];

        foreach ($order_details as $key => $order_detail) {
            $row = $this->default_report_detail($order_detail);
            $row = $this->admin_agent_report_detail($order_detail, $row);
            $row = $this->admin_margin_calculation_report_detail($order_detail, $row);
            $row = $this->admin_payment_section_report_detail($order_detail, $row);
            $row = $this->admin_pkr_cash_report_detail($order_detail, $row);
            $row = $this->admin_pkr_cash_report_recived_status_detail($order_detail, $row);
            $report_data[] = $row;
        }
        $table_info = $this->default_report_detail_info();
        $table_info = $this->admin_agent_report_detail_info($table_info);
        $table_info = $this->admin_margin_calculation_report_detail_info($table_info);
        $table_info = $this->admin_payment_section_report_detail_info($table_info);
        $table_info = $this->admin_pkr_cash_report_detail_info($table_info);
        $table_info = $this->admin_pkr_cash_report_recived_status_detail_info($table_info);

        return [
            'table_info' => $table_info,
            'report_data' => $report_data,
        ];
    }


    public function sale_agent_report_detail(Request $request)
    {
        $order_details = Order_Detail::with('order', 'driver', 'driver_user', 'travel_agent_user', 'sale_agent_user', 'transport.transport_type')
            ->latest()->get();
        $report_data = [];
        $row = [];
        $table_info = [];

        foreach ($order_details as $key => $order_detail) {
            $row = $this->default_report_detail($order_detail);
            $row = $this->admin_sales_agent_travel_agent_report_detail($order_detail, $row);
            $row = $this->admin_sales_agent_margin_report_detail($order_detail, $row);
            $row = $this->admin_sales_agent_payment_report_detail($order_detail, $row);

            $report_data[] = $row;
        }
        $table_info = $this->default_report_detail_info();
        $table_info = $this->admin_sales_agent_travel_agent_report_detail_info($table_info);
        $table_info = $this->admin_sales_agent_margin_report_detail_info($table_info);
        $table_info = $this->admin_sales_agent_payment_report_detail_info($table_info);

        return [
            'table_info' => $table_info,
            'report_data' => $report_data,
        ];
    }


    public function travel_agent_report_detail(Request $request)
    {
        $order_details = Order_Detail::with('order', 'driver', 'driver_user', 'travel_agent_user', 'sale_agent_user', 'transport.transport_type')
            ->latest()->get();
        $report_data = [];
        $row = [];
        $table_info = [];

        foreach ($order_details as $key => $order_detail) {
            $row = $this->default_report_detail($order_detail);
            $row = $this->admin_travel_agent_travel_agent_service_report_detail($order_detail, $row);
            $row = $this->admin_travel_agent_Margin_calculation_report_detail($order_detail, $row);
            $row = $this->admin_travel_agent_payment_section_report_detail($order_detail, $row);
            $report_data[] = $row;
        }
        $table_info = $this->default_report_detail_info();
        $table_info = $this->admin_travel_agent_travel_agent_service_detail_info($table_info);
        $table_info = $this->admin_travel_agent_Margin_calculation_detail_info($table_info);
        $table_info = $this->admin_travel_agent_payment_section_report_detail_info($table_info);

        return [
            'table_info' => $table_info,
            'report_data' => $report_data,
        ];
    }

    public function admin_sales_agent_payment_report_detail($order_detail, $row)
    {
        $row['admin_sales_agent_payment_type'] = $order_detail->payment_type; //not match with db
        $row['admin_sales_agent_ac_recievable_to_travel_agent'] = $order_detail->travel_agent_commission; //not match with db
        $row['admin_sales_agent_ac_payable_to_travel_agent'] = $order_detail->travel_agent_commission; //not match with db
        $row['admin_sales_agent_ac_payable_to_sales_agent'] = $order_detail->sale_agent_commission; //not match with db
        $row['admin_sales_agent_bank_credit'] = $order_detail->sale_agent_commission; //not match with db
        return $row;
    }



    public function admin_sales_agent_margin_report_detail($order_detail, $row)
    {
        $row['admin_sales_agent_margin_booking_rate'] = $order_detail->final_price; //not match with db
        $row['admin_sales_agent_margin_travel_agent_margin'] = $order_detail->travel_agent_commission; //not match with db
        $row['admin_sales_agent_margin_sales_margin'] = $order_detail->sale_agent_commission; //not match with db
        return $row;
    }


    public function admin_travel_agent_payment_section_report_detail($order_detail, $row)
    {
        $row['admin_travel_agent_payment_type'] = $order_detail->customer_collection_price; // match with db
        $row['admin_travel_agent_ac_recievable_to_travel_agent'] = $order_detail->travel_agent_commission;
        $row['admin_travel_agent_ac_payable_to_travel_agent'] = $order_detail->travel_agent_commission;
        $row['admin_travel_agent_bank_credit'] = $order_detail->driver_commission;
        return $row;
    }


    public function admin_sales_agent_payment_report_detail_info($table_info)
    {
        $table_info['admin_payment'] = [
            'heading' => 'payment',
            'color' => 'rgb(64 133 193)',
            'columns' => [
                [
                    'heading' => 'Payment Type',
                    'data_column' =>  'admin_sales_agent_payment_type', //match with pre function
                ],
                [
                    'heading' => 'A/c recievable to Trav Agent',
                    'data_column' =>  'admin_sales_agent_ac_recievable_to_travel_agent',
                ],
                [
                    'heading' => 'A/c Payable to Travel Agent',
                    'data_column' =>  'admin_sales_agent_ac_payable_to_travel_agent',
                ],
                [
                    'heading' => 'A/c Payable to Sales Agent',
                    'data_column' =>  'admin_sales_agent_ac_payable_to_sales_agent',
                ],
                [
                    'heading' => 'Bank Credit',
                    'data_column' =>  'admin_sales_agent_bank_credit',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_sales_agent_margin_report_detail_info($table_info)
    {
        $table_info['admin_margin'] = [
            'heading' => 'Margin',
            'color' => '#ffff1f',
            'columns' => [
                [
                    'heading' => 'Booking Rate',
                    'data_column' =>  'admin_sales_agent_margin_booking_rate', //match with pre function
                ],
                [
                    'heading' => 'Travel agent margin',
                    'data_column' =>  'admin_sales_agent_margin_travel_agent_margin',
                ],
                [
                    'heading' => 'Sales Margin',
                    'data_column' =>  'admin_sales_agent_margin_sales_margin',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_sales_agent_travel_agent_report_detail($order_detail, $row)
    {
        $row['admin_sales_agent_travel_agent'] = $order_detail->travel_agent_user->name ?? ''; //not match with db
        $row['admin_sales_agent_sales_agentt'] = $order_detail->sale_agent_user->name ?? ''; //not match with db
        $row['admin_sales_agent_service_type'] = $order_detail->journey->name ?? ''; //not match with db
        return $row;
    }


    public function admin_sales_agent_travel_agent_report_detail_info($table_info)
    {
        $table_info['admin_sales_agent_'] = [
            'heading' => 'travel sales service',
            'color' => 'rgb(248 203 173)',
            'columns' => [
                [
                    'heading' => 'Travel Agent',
                    'data_column' =>  'admin_sales_agent_travel_agent', //match with pre function
                ],
                [
                    'heading' => 'Sales Agent',
                    'data_column' =>  'admin_sales_agent_sales_agentt',
                ],
                [
                    'heading' => 'SERVICE TYPE',
                    'data_column' =>  'admin_sales_agent_service_type',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_margin_calculation_report_detail($order_detail, $row)
    {
        $row['admin_margin_calculation_booking_rate'] = $order_detail->customer_collection_price; // match with db
        $row['admin_margin_calculation_travel_agent'] = $order_detail->travel_agent_commission;
        $row['admin_margin_calculation_sales_agent'] = $order_detail->sale_agent_commission;
        $row['admin_margin_calculation_hire_rate'] = $order_detail->driver_commission;
        $row['admin_margin_calculation_office_profit'] = $order_detail->office_profit;

        return $row;
    }


    public function admin_travel_agent_travel_agent_service_report_detail($order_detail, $row)
    {
        $row['admin_travel_agent_travel_agent'] = $order_detail->travel_agent_user->name ?? ''; // match with db //not match 
        $row['admin_travel_agent_serivce_type'] = $order_detail->journey->name ?? '';

        return $row;
    }


    public function admin_travel_agent_Margin_calculation_report_detail($order_detail, $row)
    {
        $row['admin_travel_agent_booking_rate'] = $order_detail->final_price; // match with db //not match 
        $row['admin_travel_agent_travel_agent_margin'] = $order_detail->travel_agent_commission;
        return $row;
    }


    public function admin_pkr_cash_report_detail($order_detail, $row)
    {
        $row['admin_cash_received_in_pakistan_petty_cash'] = $this->cash_recieved_in_pakistan($order_detail) ? $order_detail->customer_collection_price : 0;
        $row['admin_cash_received_in_saudia_petty_cash'] = $this->cash_recieved_in_saudia($order_detail) ? $order_detail->customer_collection_price : 0;
        return $row;
    }
    public function admin_pkr_cash_report_recived_status_detail($order_detail, $row)
    {
        $row['admin_cash_received_status_admin'] = $order_detail->admin_payment_status;
        $row['admin_cash_received_status_sale_agent'] = $order_detail->sale_agent_payment_status;
        $row['admin_cash_received_status_travel_agent'] = $order_detail->travel_agent_payment_status;
        return $row;
    }
    public function cash_recieved_in_pakistan($order_detail)
    {
        return $order_detail->payment_type == Config::get('constants.payment_type.advance_collection');
    }

    public function cash_recieved_in_saudia($order_detail)
    {
        return $order_detail->payment_type == Config::get('constants.payment_type.cod');
    }

    public function amount_collected_admin($order_detail)
    {
        return $order_detail->cash_collected_by_role == 1;
    }
    public function amount_collected_sale_agent($order_detail)
    {
        return $order_detail->cash_collected_by_role == 3;
    }
    public function amount_collected_travel_agent($order_detail)
    {
        return $order_detail->cash_collected_by_role == 4;
    }
    public function amount_collected_driver($order_detail)
    {
        return $order_detail->cash_collected_by_role == 5;
    }
    public function driver_is_own($order_detail)
    {
        return $order_detail->driver && $order_detail->driver->driver_category == Config::get('constants.driver.categories_keys.own');
    }
    public function driver_is_hired($order_detail)
    {
        return $order_detail->driver && $order_detail->driver->driver_category == Config::get('constants.driver.categories_keys.out_source');
    }
    public function is_bank_credit($order_detail)
    {
        return $order_detail->payment_type == Config::get('constants.payment_type.card');
    }


    public function admin_payment_section_report_detail($order_detail, $row)
    {
        // $row['admin_payment_section_customer_collection_price'] = $order_detail->customer_collection_price ?? ''; // match with db
        $row['admin_payment_section_payment_type'] = $order_detail->payment_type ?? ''; // match with db
        $row['admin_payment_section_ac_receivable_travel_agent'] = $this->amount_collected_travel_agent($order_detail) ? $order_detail->payable_to_admin : 0;
        $row['admin_payment_section_ac_payable_travel_agent'] = $this->amount_collected_travel_agent($order_detail) ? 0 : $order_detail->travel_agent_commission;
        $row['admin_payment_section_ac_payable_sales_agent'] = $this->amount_collected_sale_agent($order_detail) ? 0 : $order_detail->sale_agent_commission;
        $row['admin_payment_section_a_reciveble_to_hired_vehicle'] = $this->driver_is_hired($order_detail) ?
            ($this->amount_collected_driver($order_detail) ? $order_detail->payable_to_admin : 0) : 0;
        $row['admin_payment_section_ac_payable_to_hired_vehicle'] = $this->driver_is_hired($order_detail) ?
            ($this->amount_collected_driver($order_detail) ? 0 : $order_detail->driver_commission) : 0;
        $row['admin_payment_section_ac_receivable_to_owner_driver'] = $this->driver_is_own($order_detail) ?
            ($this->amount_collected_driver($order_detail) ? $order_detail->payable_to_admin : 0) : 0;
        $row['admin_payment_section_ac_payable_to_owner_vehicle'] = $this->driver_is_own($order_detail) ?
            ($this->amount_collected_driver($order_detail) ? 0 : $order_detail->driver_commission) : 0;
        $row['admin_payment_section_bank_credit'] = $this->is_bank_credit($order_detail) ? $order_detail->customer_collection_price : 0;
        return $row;
    }


    public function admin_agent_report_detail($order_detail, $row)
    {
        // $row['admin_agent_direct_customer'] = $order_detail->order->user_obj->role_id == 2 ?
        //     $order_detail->order->user_obj->name : '';
        $row['admin_agent_direct_customer'] = $order_detail->order->customer_name;
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
        $table_info['admin_margin_calculation'] = [
            'heading' => 'Margin Calculation',
            'color' => 'hsl(60deg 100% 50%)',
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


    public function admin_travel_agent_travel_agent_service_detail_info($table_info)
    {
        $table_info['admin_travel_agent'] = [
            'heading' => 'Travel Agent',
            'color' => 'rgb(248 203 173)',
            'columns' => [
                [
                    'heading' => 'Travel Agent',
                    'data_column' =>  'admin_travel_agent_travel_agent', //match with pre function
                ],
                [
                    'heading' => 'SERVICE TYPE',
                    'data_column' =>  'admin_travel_agent_serivce_type',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_travel_agent_payment_section_report_detail_info($table_info)
    {
        $table_info['payment_section'] = [
            'heading' => 'Payment Section',
            'color' => 'rgb(64 133 193)',
            'columns' => [
                [
                    'heading' => 'BOOKING RATE',
                    'data_column' =>  'admin_travel_agent_payment_type', //match with pre function
                ],
                [
                    'heading' => 'Travel agent margin',
                    'data_column' =>  'admin_travel_agent_ac_recievable_to_travel_agent',
                ],
                [
                    'heading' => 'A/c Payable to Travel Agent',
                    'data_column' =>  'admin_travel_agent_ac_payable_to_travel_agent', //match with pre function
                ],
                [
                    'heading' => 'Bank Credit',
                    'data_column' =>  'admin_travel_agent_bank_credit',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_travel_agent_Margin_calculation_detail_info($table_info)
    {
        $table_info['margin_calculation'] = [
            'heading' => 'Margin Calculation',
            'color' => 'rgb(189,215,238)',
            'columns' => [
                [
                    'heading' => 'BOOKING RATE',
                    'data_column' =>  'admin_travel_agent_booking_rate', //match with pre function
                ],
                [
                    'heading' => 'Travel agent margin',
                    'data_column' =>  'admin_travel_agent_travel_agent_margin',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_pkr_cash_report_recived_status_detail_info($table_info)
    {
        $table_info['admin_cash_received_details'] = [
            'heading' => 'Payment Status',
            'color' => 'rgb(255, 255, 0)',
            'columns' => [
                [
                    'heading' => 'Admin Payment Status',
                    'data_column' =>  'admin_cash_received_status_admin', //match with pre function
                ],
                [
                    'heading' => 'Sale agent payment status',
                    'data_column' =>  'admin_cash_received_status_sale_agent',
                ],
                [
                    'heading' => 'Travel agent payment status',
                    'data_column' =>  'admin_cash_received_status_travel_agent',
                ],
            ],
        ];
        return $table_info;
    }
    public function admin_pkr_cash_report_detail_info($table_info)
    {
        $table_info['admin_petty_cash_received'] = [
            'heading' => 'Cash Received',
            'color' => 'rgb(64 133 193)',
            'columns' => [
                [
                    'heading' => 'Cash Received In Pakistan Petty Cash',
                    'data_column' =>  'admin_cash_received_in_pakistan_petty_cash', //match with pre function
                ],
                [
                    'heading' => 'Cash Received In Saudia Petty Cash',
                    'data_column' =>  'admin_cash_received_in_saudia_petty_cash',
                ],
            ],
        ];
        return $table_info;
    }


    public function admin_agent_report_detail_info($table_info)
    {
        $table_info['admin_agent_details'] = [
            'heading' => 'Agent Details',
            'color' => '#ffc9ab',
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


    public function admin_payment_section_report_detail_info($table_info)
    {
        $table_info['admin_payment_section'] = [
            'heading' => 'Payment Section',
            'color' => '#b5d8ee',
            'columns' => [

                [
                    'heading' => 'Payment Type',
                    'data_column' =>  'admin_payment_section_payment_type',
                ],
                [
                    'heading' => 'A/C Receivable To tavel agent',
                    'data_column' =>  'admin_payment_section_ac_receivable_travel_agent',
                ],
                [
                    'heading' => 'A/C Payable To tavel agent',
                    'data_column' =>  'admin_payment_section_ac_payable_travel_agent',
                ],
                [
                    'heading' => 'A/C Payable To Sales agent',
                    'data_column' =>  'admin_payment_section_ac_payable_sales_agent',
                ],
                [
                    'heading' => 'A/C Receivable To Hired Vehicle',
                    'data_column' =>  'admin_payment_section_a_reciveble_to_hired_vehicle',
                ],
                [
                    'heading' => 'A/C Payable To Hired Vehicle',
                    'data_column' =>  'admin_payment_section_ac_payable_to_hired_vehicle',
                ],
                [
                    'heading' => 'A/C Receivable To Own Driver',
                    'data_column' =>  'admin_payment_section_ac_receivable_to_owner_driver',
                ],
                [
                    'heading' => 'A/C Payable To Own Vehicle',
                    'data_column' =>  'admin_payment_section_ac_payable_to_owner_vehicle',
                ],
                [
                    'heading' => 'Bank Credit',
                    'data_column' =>  'admin_payment_section_bank_credit',
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
                'color' => '#ffe599',
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
                'color' => '#ffc9ab',
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
                'color' => '#ffe599',
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



    public function driver_report_detail(Request $request)
    {
    }
}
