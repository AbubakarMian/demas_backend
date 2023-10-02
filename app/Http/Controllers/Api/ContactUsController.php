<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{

    public function contactus(Request $request)
    {
        try {

            // dd($request->all());
            // $validator = Validator::make($request->all(), User::$rules_register);

            // if ($validator->fails()) {
            //     return $this->sendResponse(500, null, $validator->messages()->all());
            // } else {

                $contact = new ContactUs();
                $contact->name = $request->name;
                $contact->email = $request->email;
                $contact->whatsapp_number = $request->whatsapp_number;
                $contact->message = $request->message;
                $contact->save();

               
                return $this->sendResponse(200, $contact);
            // }
        } catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }}
