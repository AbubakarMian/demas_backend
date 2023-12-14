<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewAgent as ModelsNewAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NewAgentController extends Controller
{
    public function new_agent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'phone_no' => 'required',
                'whatsapp_number' => 'required',
                'password' => 'required|min:6',
 
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator); // Use the correct exception
            } else {
                $new_agent = new ModelsNewAgent();
                $new_agent->name = $request->name;
                $new_agent->email = $request->email;
                $new_agent->phone_no = $request->phone_no;
                $new_agent->whatsapp_number = $request->whatsapp_number;
         
                $new_agent->password = Hash::make($request->password);
                $new_agent->save();

                return $this->sendResponse(200, $new_agent);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(500, null, [$e->getMessage()]);
        }
    }
}
