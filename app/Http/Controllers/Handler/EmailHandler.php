<?php

namespace App\Http\Controllers\Handler;

// use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Mail\SendGeneralEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
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
    use Common; //,APIResponse;

    public function __constructor()
    {
    }

    public function sendEmail($email_detail)
    {
        if (Config::get('app.env') == 'production') {
            $email_detail['bcc'][] = [
                'from_email' => 'abubakarhere90@gmail.com',
                'from_name' => 'Abubakar here bcc',
            ];
            $email_detail['bcc'][] = [
                'from_email' => 'abubakrmianmamoon@gmail.com',
                'from_name' => 'Abubakar bcc',
            ];
            $email_detail['bcc'][] = [
                'from_email' => 'info@demas.com',
                'from_name' => 'Admin',
            ];
            Log::debug('--------email details----------', [$email_detail]);
            Mail::send($email_detail['view'], ['data' => $email_detail['data']], function ($message) use ($email_detail) {
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
            });
        }
    }
}
