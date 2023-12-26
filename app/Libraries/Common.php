<?php

namespace App\Libraries;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Response;
use DB;
use Illuminate\Support\Facades\Log;

// use Excel;
// use Excel;

trait Common
{

    public function send_whatsapp_sms($whats_app_number, $text)
    {
        $id_instance = Config::get('whatsapp.WHATSAPP_ID');
        $apiTokenInstance = Config::get('whatsapp.WHATSAPP_TOKEN');
        $url = 'https://api.green-api.com/waInstance' . $id_instance . '//sendMessage/' . $apiTokenInstance;
        // $url = 'https://api.green-api.com/waInstance{{idInstance}}/sendMessage/{{apiTokenInstance}}';

        //chatId is the number to send the message to (@c.us for private chats, @g.us for group chats)
        $data = array(
            'chatId' => '71234567890@c.us',
            'message' => 'Hello World'
        );

        $options = array(
            'http' => array(
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);

        $response = file_get_contents($url, false, $context);

        echo $response;
    }

    public function get_absolute_server_url_path($path){
        // https://demastransport.com/demas_backend/public/invoice/3pdf
        // /home/demastransport/public_html/demas_backend/public/invoice/3pdf
        $str = str_replace('/home/demastransport/public_html/demas_backend/public','',
            $path
        );
        $str = asset($str);
        return $str;
    }
    // $whats_app_number="=9233437222073";
    public function send_url_file_whatsapp($whats_app_number,$file_url){
        
        $id_instance = Config::get('whatsapp.WHATSAPP_ID');
        $apiTokenInstance = Config::get('whatsapp.WHATSAPP_TOKEN');
        // $url = "https://api.green-api.com/waInstance".$id_instance."/sendFileByUrl/".$apiTokenInstance;
        $url = "https://api.green-api.com/waInstance{$id_instance}/sendFileByUrl/{$apiTokenInstance}";
        $payload = json_encode([
            'chatId' => 'mailto:'.$whats_app_number.'@c.us',
            'urlFile' => $file_url,
            // 'urlFile' => 'https://avatars.mds.yandex.net/get-pdb/477388/77f64197-87d2-42cf-9305-14f49c65f1da/s375',
            'fileName' => 'invoice.pdf',
            'caption' => 'Details'
            // 'caption' => 'лошадка'
        ]);
        
        $headers = [
            'Content-Type: application/json'
        ];
        

        Log::info('-----------start whats app ----------');

        Log::info('-----------url ----------');
        Log::info($url);
        Log::info('-----------payload ----------');
        Log::info($payload);
// dd('www');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        echo $response;
    }

    public function sendFile_whatsapp()
    {
        $id_instance = Config::get('whatsapp.');
        $apiTokenInstance = Config::get('whatsapp.');

        $url = 'https://api.green-api.com/waInstance' . $id_instance . '//sendFileByUpload/' . $apiTokenInstance;

        $payload = [
            'chatId' => '11001234567@c.us',
            'caption' => 'Описание'
        ];

        $files = [
            'file' => new CURLFile('C:/window.jpg', 'image/jpeg', 'window.jpg')
            // 'file' => new CURLFile('C:/window.jpg', 'image/jpeg', 'window.jpg')
        ];

        $headers = [];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload + $files);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        echo $response;
    }
    public function prepare_excel($data, $field_not_required = [])
    {
        $users = [];
        foreach ($data as $rec_key => $value) {
            foreach ($value as $key => $v) {
                if (!in_array($key, $field_not_required)) {
                    $users[$rec_key][str_replace("_", " ", $key)] = $v;
                }
            }
        }
        return $users;
    }

    public function move_img_get_path($image, $root, $type, $image_name = '')
    {
        $uniqid = time();
        $extension = mb_strtolower($image->getClientOriginalExtension());
        $name = $uniqid . $image_name . '.' . $extension; //.$image->getClientOriginalName();
        $imgPath = public_path() . '/images/' . $type;
        $image->move($imgPath, $name);
        $remove_index = str_replace("index.php", "", $root);
        return $remove_index . '/images/' . $type . '/' . $name;
    }
    // public function export_excel($report_name,$users){

    //     Excel::create($report_name, function ($excel) use ($users) {
    //         $excel->sheet('Sheet 1', function ($sheet) use ($users) {
    //             $sheet->fromArray($users);
    //         });
    //     })->export('xls');

    // }

    function get_embeddedyoutube_url($url)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "//www.youtube.com/embed/$2",
            $url
        );
    }

    public function sort_asc_array($arr, $column)
    {
        usort($arr, function ($a, $b) use ($column) {
            return $a[$column] <=> $b[$column];
        });
        return $arr;
    }
}
