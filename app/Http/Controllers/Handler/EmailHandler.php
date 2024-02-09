<?php

namespace App\Http\Controllers\Handler;

// use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Mail\SendGeneralEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Handler\OrderHandler;

use App\Models\Order;
// use App\Models\Slot;
// use App\Models\Transport;
// use App\Models\TransportPrices;
// use App\Models\Travel_Agent;
// use App\Models\TravelAgentCommission;
// use Illuminate\Http\Request;

class EmailHandler
{
    use Common; //,APIResponse;

    public function __constructor()
    {
    }

    public function send_invoice($order_id){

        $order = Order::with([
            'user_obj',
            'sale_agent',
            'travel_agent',
            // 'order_details' => [
            'active_order_details' => [
                'driver'=>['user_obj'],
                'transport_type','transport', 'journey' => ['pickup', 'dropoff']
            ],
        ])->find($order_id);
        
        $order_handler = new OrderHandler();

        $pdf = $order_handler->gernerate_pdf_order($order_id);
        // dd($pdf );
        $receipt_url = $pdf['path'];
        $whast_app_url = $this->get_absolute_server_url_path($receipt_url);

       $this->send_url_file_whatsapp($order->customer_whatsapp_number,$whast_app_url);
    //    $this->send_url_file_whatsapp($order->customer_whatsapp_number,$whast_app_url);

        // $email_handler = new EmailHandler();
        $email_details = [];
        $user = $order->user_obj;
        $email_details['subject'] = 'Demas Invoice';
        $email_details['attachments'][] = $receipt_url;
        $email_details['to_email'] = $user->email;
        $email_details['to_name'] =  $user->name;
        $email_details['data'] = [
            'user'=>$user,
            'order'=>$order,
        ];
        
        $email_details['view'] = 'pdf.order_update_email';
        $this->sendEmail($email_details);
    }

    public function sendEmail($email_detail)
    {
        if (Config::get('app.env') == 'production') {
            // dd("this is email");

            $email_detail['bcc'][] = [
                'from_email' => 'abubakarhere90@gmail.com',
                'from_name' => 'Abubakar here bcc',
            ];
            // $email_detail['bcc'][] = [
            //     'from_email' => 'abubakrmianmamoon@gmail.com',
            //     'from_name' => 'Abubakar bcc',
            // ];

            $email_detail['bcc'][] = [
                'from_email' => 'umrahtransportdemas@gmail.com',
                'from_name' => 'Admin',
            ];
            $email_detail['bcc'][] = [
                'from_email' => 'info@demas.com',
                'from_name' => 'Admin',
            ];
            Log::debug('--------email details----------', [$email_detail]);
            Mail::send(
                $email_detail['view'],
                ['data' => $email_detail['data']],
                function ($message) use ($email_detail) {
                    if (!isset($email_detail['from_email'])) {
                        $email_detail['from_email'] = 'admin@demas.com';
                    }

                    if (!isset($email_detail['from_name'])) {
                        $email_detail['from_name'] = 'Demas';
                    }
                    if (isset($email_detail['subject'])) {
                        $message->subject($email_detail['subject']);
                    }
                    if (isset($email_detail['cc'])) {
                        foreach ($email_detail['cc'] as $key => $cc) {

                            $message->cc($cc['from_email'], $cc['from_name']);
                        }
                    }
                    if (isset($email_detail['bcc'])) {
                        foreach ($email_detail['bcc'] as $key => $bcc) {
                            $message->bcc($bcc['from_email'], $bcc['from_name']);
                        }
                    }
                    if (isset($email_detail['attachments'])) {
                        foreach ($email_detail['attachments'] as $key => $attachment) {
                            $message->attach($attachment);
                        }
                    }

                    $message->from($email_detail['from_email'], $email_detail['from_name']);
                    $message->to($email_detail['to_email'], $email_detail['to_name']);
                }
            );
        }
    }
}
