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

    public function send_driver_info($number,$order,$order_detail){

      $message = "Dear {$order->user_obj->name}, your invoice details are as follows:\n" .
      "https://wa.me/{$order->user_obj->phone_no}\n" .
      "10:30 AM  – time will be added manually\n" .
      "PAX:{$order->total_passengers}\n" .
      "Cash From Customer: {$order_detail->final_price} SAR\n" .
      "Vehicle: (Hyundai H-1)\n" .
      "Extra Information:{$order_detail->pick_extrainfo}\n";

  // Add more details to the message as needed
  $message .= "Order ID: {$order->id}\n";
  // ...

  try {
      $this->send_whatsapp_sms($number, $message);
      // Handle success or redirect as needed
  } catch (Exception $e) {
      // Handle the exception (log, display an error message, etc.)
      echo "Error: " . $e->getMessage();
  }
    }

}
