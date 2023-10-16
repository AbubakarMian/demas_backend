<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\EmailHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;


class UserController extends Controller
{
    public function register_or_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone_no' => 'required',
                'email' => 'email',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse(500, null, $validator->messages()->all());
            } else {

                $name = 'user';
                if ($request->email) {
                    $name = explode('@', $request->email)[0];
                }
                $user = User::where('phone_no', $request->phone_no)->first();
                if (!$user) {
                    $user = new User();
                }
                if ($request->email) {
                    $user->email = $request->email;
                }
                $user->phone_no = $request->phone_no;
                // $user->password = Hash::make($request->password);
                $user->access_token = uniqid();
                $user->otp = rand(10000, 99999);
                $user->save();

                if ($request->email) {

                    $email_handler = new EmailHandler();
                    $email_details = [];
                    $email_details['cc'] =[];
                    $email_details['cc'][] = [
                        'from_email' => 'abubakrmianmamoon+cc@gmailcom',
                        'from_name' => 'Abubakar cc',
                    ];
                    $email_details['cc'][] = [
                        'from_email' => 'saadyasirthegreat+cc@gmailcom',
                        'from_name' => 'Saad cc',
                    ];
                    $email_details['subject'] = 'Demas OTP';
                    $email_details['to_email'] = 'abubakrmianmamoon@gmail.com';
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

                $user = User::where('otp', $request->otp)->first();
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
        } catch (\Exception $e) {
            return [
                'status' => Config::get('error.code.INTERNAL_SERVER_ERROR'),
                'response' => null,
                'error' => [$e->errorInfo[2]],
                'custom_error_code' => $e->errorInfo[0]
            ];
        }
    }
}
