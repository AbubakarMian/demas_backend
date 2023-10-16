<?php

namespace App\Http\Controllers\Handler;

// use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Mail\SendGeneralEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

// use App\Models\Journey;
// use App\Models\Slot;
// use App\Models\Transport;
// use App\Models\TransportPrices;
// use App\Models\Travel_Agent;
// use App\Models\TravelAgentCommission;
// use Illuminate\Http\Request;

class EmailHandler 
{
    use Common;//,APIResponse;

    public function __constructor(){

    }

    public function sendEmail($email_detail){
        if(Config::get('app.env') == 'production'){
            $send_email  = new SendGeneralEmail($email_detail);
            // Mail::to($request->user())->send(new OrderShipped($order));
            Mail::assertSent($send_email);
        }
       
    }
}
