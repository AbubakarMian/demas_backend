<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Order_Detail;
use App\Models\Settings;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WalletHandler
{
    use Common, APIResponse;

    public function __constructor()
    {
    }

    public function user_balance_information()
    {
        $users = Users::with('un_paid_to_admin_order_details')->get();
    
        $users->transform(function ($user) {
            $unpaid_amount = 0;
    // dd($user->un_paid_to_admin_order_details);
            // Check if $user->un_paid_to_admin_order_details is not null before iterating
            if ($user->un_paid_to_admin_order_details !== null) {
                foreach ($user->un_paid_to_admin_order_details as $un_paid_order) {
                    // dd($un_paid_order->payable_to_admin);
                    $unpaid_amount += $un_paid_order->payable_to_admin;
                }
            }
    
            $user->unpaid_amount = $unpaid_amount;
            return $user;
        });
    
        return $users;
    }
    
}
