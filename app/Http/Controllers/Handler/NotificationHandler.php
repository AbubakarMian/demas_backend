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

    public function send_driver_info($number, $order, $order_detail) {
      // Extract date and time
      $date = isset($order->active_order_details[0]->pick_up_date_time) ? date('d-m-Y', $order->active_order_details[0]->pick_up_date_time) : '';
      $time = isset($order->active_order_details[0]->manual_time_set) ? date('H:i:s', $order->active_order_details[0]->manual_time_set) : '';
  
      // Extract other details
      $user_name = isset($order->user_obj->name) ? $order->user_obj->name : '';
      $customer_whatsapp_number = isset($order->customer_whatsapp_number) ? $order->customer_whatsapp_number : '';
      $pickup_name = isset($order->active_order_details[0]->journey->pickup->name) ? $order->active_order_details[0]->journey->pickup->name : '';
      $dropoff_name = isset($order->active_order_details[0]->journey->dropoff->name) ? $order->active_order_details[0]->journey->dropoff->name : '';
      $total_passengers = isset($order->active_order_details[0]->total_passengers) ? $order->active_order_details[0]->total_passengers : '';
      $final_price = isset($order->final_price) ? $order->final_price : '';
      $transport_name = isset($order->active_order_details[0]->transport->name) ? $order->active_order_details[0]->transport->name : '';
      $extra_info = isset($order->active_order_details[0]->pick_extrainfo) ? $order->active_order_details[0]->pick_extrainfo : '';
  
      // Construct the message
      $message = "Dear {$user_name}, your invoice details are as follows:\n" .
          "https://wa.me/{$customer_whatsapp_number}\n" .
          "Date:\t {$date}\n" .
          "Time:\t {$time}\n" .
          "PickUp:\t{$pickup_name}\n" .
          "DropOff:\t{$dropoff_name}\n" .
          "PAX:\t{$total_passengers}\n" .
          "Cash From Customer:\t {$final_price} SAR\n" .
          "Vehicle:\t ({$transport_name})\n" .
          "Extra Information:\t{$extra_info}\n";
  
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
