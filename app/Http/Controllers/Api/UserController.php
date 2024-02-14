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
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

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
                $phoneUtil = PhoneNumberUtil::getInstance();

                try {
                    $phone_no = $request->phone_no ? $phoneUtil->parse($request->phone_no, null) : '';
                    $whatsapp_no = $phoneUtil->parse($request->whatsapp_no, null);
                    $whatsapp_no = $phoneUtil->format($whatsapp_no, PhoneNumberFormat::E164);
                } catch (NumberParseException $e) {
                    return $this->sendResponse(500, null, ["Error parsing phone number: " . $e->getMessage()]);
                }

                // Rest of your code...
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

                //Set the JSON response
                $status_code = $responseArray['status'];
                $response = $responseArray['response'];
                $error = $responseArray['error'];
                $custom_error_code = $responseArray['custom_error_code'];

                return $this->sendResponse($status_code, $response, $error, $custom_error_code);
            }
        } catch (\Exception $e) {
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
