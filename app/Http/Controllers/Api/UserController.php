<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\EmailHandler;
use App\Http\Controllers\Handler\NotificationHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;


class UserController extends Controller
{

    public function send_msg()
    {
        $msg = new NotificationHandler();
        $m = $msg->send_notification();
        dd($m);
    }
    public function register_or_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'phone_no' => 'required',
                'whatsapp_no' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(500, null, $validator->messages()->all());
            } else {

                $user_phone = User::where('phone_no', $request->phone_no)
                    ->first();

                $user_whatsapp_no = User::where('whatsapp_number', $request->whatsapp_no)
                    ->first();

                $user_email = User::where('email', $request->email)
                    ->first();

                $user = User::where('whatsapp_number', $request->whatsapp_no)
                    ->where('email', $request->email)
                    ->first();

                if (($user_whatsapp_no || $user_email) && !$user) {
                    $msg = '';
                    $msg = $user_whatsapp_no ? 'Phone number already taken' : '';
                    $msg .= $user_email ? ' Email already taken' : '';

                    return $this->sendResponse(500, null, [$msg]);
                }
                // if( ($user_phone || $user_email) && !$user){
                //     $msg = '';
                //     $msg = $user_phone ? 'Phone number already taken':'';
                //     $msg .= $user_email ? ' Email already taken':'';

                //     return $this->sendResponse(500, null, [$msg]);
                // }


                $name = 'user';
                if ($request->email) {
                    $name = explode('@', $request->email)[0];
                }
                // $user = User::where('phone_no', $request->phone_no)->first();
                if (!$user) {
                    $user = new User();
                    $user->role_id = 2;
                    $user->name = $name;
                    $user->last_name = '';
                }
                if ($request->email) {
                    $user->email = $request->email;
                }
                // $user->phone_no = $request->phone_no;
                $user->phone_no = $request->phone_no ?? $request->whatsapp_no;
                $user->whatsapp_number = $request->whatsapp_no;
                // $user->password = Hash::make($request->password);
                $user->access_token = uniqid();
                $user->otp = rand(10000, 99999);
                $user->save();

                if ($request->email) {

                    $email_handler = new EmailHandler();
                    $email_details = [];

                    $email_details['subject'] = 'Demas OTP';
                    // $email_details['to_email'] = 'abubakrmianmamoon@gmail.com';
                    $email_details['to_email'] = $user->email;
                    $email_details['to_name'] = 'Abubakar';
                    $email_details['data'] = $user;
                    $email_details['view'] = 'email_template.otp';
                    $email_handler->sendEmail($email_details);
                }

                return $this->sendResponse(200, $user);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }
    public function validate_otp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(500, null, $validator->messages()->all());
            } else {

                $user = User::where('otp', $request->otp)
                    ->where('access_token', $request->access_token)->first();
                if (!$user) {
                    return $this->sendResponse(500, null, ['Invalid OTP']);
                }
                return $this->sendResponse(200, $user);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }


    public function login(Request $request)
    {
        try {
            //Request input Validation
            $validation = Validator::make($request->all(), User::$rules);
            if ($validation->fails()) {
                return $this->sendResponse(
                    Config::get('error.code.BAD_REQUEST'),
                    null,
                    $validation->getMessageBag()->all(),
                    Config::get('error.code.BAD_REQUEST')
                );
            } else {
                $authUser = Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]);

                //Get record if user has authenticated
                if ($authUser) {
                    $device = $request->header('client-id');
                    $user = User::where([
                        'email' => $request->email
                    ])->get([
                        'access_token',
                        'id',
                        'name',
                        'email',
                        // 'avatar',
                        'access_token',
                        // 'get_notification'
                    ])
                        ->first();

                    $user->access_token = uniqid();
                    // $user->device_type = $device;
                    $user->save();
                    $user->get_notification = ($user->get_notification ? true : false);

                    // unset($user->device_type);
                    $responseArray =  [
                        'status' => Config::get('constants.status.OK'),
                        'response' => $user,
                        'error' => null,
                        'custom_error_code' => null
                    ];
                } else {
                    $responseArray =  [
                        'status' => Config::get('error.code.NOT_FOUND'),
                        'response' => null,
                        'error' => [Config::get('error.message.USER_NOT_FOUND')],
                        'custom_error_code' => Config::get('error.code.NOT_FOUND')
                    ];
                }

                // end sad

                //Set the JSON response
                $status_code = $responseArray['status'];
                $response = $responseArray['response'];
                $error = $responseArray['error'];
                $custom_error_code = $responseArray['custom_error_code'];

                return $this->sendResponse($status_code, $response, $error, $custom_error_code);
            }
        }catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }
    public function user_update_profile(Request $request)
    {
        try {
            $user = $request->attributes->get('user');

            $validation = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_no' => 'required|unique:users,phone_no,' . $user->id,
                'whatsapp_number' => 'required|unique:users,whatsapp_number,'. $user->id,
            ]);

            if ($validation->fails()) {
                return $this->sendResponse(
                    Config::get('error.code.BAD_REQUEST'),
                    null,
                    $validation->getMessageBag()->all(),
                    Config::get('error.code.BAD_REQUEST')
                );
            } else {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone_no = $request->phone_no;
                $user->whatsapp_number = $request->whatsapp_number;
                $user->save();

                return $this->sendResponse(200, $user);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }
}
