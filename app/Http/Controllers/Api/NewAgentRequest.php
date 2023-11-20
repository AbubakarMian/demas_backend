<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewAgentRequest as ModelsNewAgentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class NewAgentRequest extends Controller
{

    public function create_request_new_agent(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'email'=>'required|email',
                'phone'=>'required',
                'whatsapp'=>'required',
                'password'=>'required|min:6',
            ]);
    
            if ($validator->fails()) {
                return $this->sendResponse(500, null, $validator->messages()->all());
            } else {
    
                $new_agent = new ModelsNewAgentRequest();
                $new_agent->name = $request->name;
                $new_agent->email = $request->email;
                $new_agent->phone = $request->phone;
                $new_agent->whatsapp = $request->whatsapp;
                $new_agent->comments = $request->comments;
                $new_agent->password = Hash::make($request->password);
                $new_agent->save();
    
                return $this->sendResponse(200, $new_agent);
            }
        }
         catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }
}
