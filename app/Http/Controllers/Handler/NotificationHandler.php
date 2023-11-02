<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\SaleAgent;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;

class NotificationHandler
{
    use Common, APIResponse;

    public function send_notification()
    {
        $sid = "ACXXXXXX"; // Your Account SID from www.twilio.com/console
        $token = "YYYYYY"; // Your Auth Token from www.twilio.com/console
        
        // $client = new Twilio\Rest\Client($sid, $token);
        $client = new Client(Config::get('services.twilio.sid'), Config::get('services.twilio.token'));
        $message = $client->messages->create(
        //   '8881231234', // Text this number
          '+923323715731', // Text this number
        //   '+923323715731', // Text this number
          [
            'from' => Config::get('services.twilio.whatsapp_from'), // From a valid Twilio number
            'body' => 'Hello from Twilio!'
          ]
        );
        
        print $message->sid;
        return $message;
    }

}
