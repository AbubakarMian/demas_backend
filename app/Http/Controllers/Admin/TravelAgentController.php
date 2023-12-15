<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\TripCommissionHandler;
use App\Models\SaleAgent;
use App\Models\Travel_Agent;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TravelAgentController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.travel_agent.index');
    }

    public function get_travel_agent(Request $request)
    {
        $travel_agent = Travel_Agent::with('user_obj')->orderBy('created_at', 'DESC')->get();
        $travel_agentData['data'] = $travel_agent;
        echo json_encode($travel_agentData);
    }

    public function create()
    {
        $control = 'create';
        $user_sale_agents = SaleAgent::with('user_obj')->get()->pluck('user_obj.name', 'user_obj.id');
        return view('admin.travel_agent.create', compact(
            'control',
            'user_sale_agents'
        ));
    }

    public function save(Request $request)
    {
        $travel_agent = new Travel_Agent();
        $user = new User();
        $travel_agent = $this->add_or_update($request, $user, $travel_agent);
        $trip_commission_handler = new TripCommissionHandler();
        $trip_commission_handler->create_sale_agent_trip_prices([],[$travel_agent]);
        return Redirect('admin/travel_agent');
        
    }
    public function edit($id)
    {
        $control = 'edit';
        $travel_agent = Travel_Agent::with('user_obj')->find($id);
        $user = $travel_agent->user_obj;
        $user_sale_agents = SaleAgent::with('user_obj')->get()->pluck('user_obj.name', 'user_obj.id');
        return view(
            'admin.travel_agent.create',
            compact(
                'control',
                'travel_agent',
                'user',
                'user_sale_agents',
            )
        );
    }

    public function update(Request $request, $id)
    {
        $travel_agent = Travel_Agent::with('user_obj')->find($id);
        $user = $travel_agent->user_obj;
        $travel_agent =  $this->add_or_update($request, $user, $travel_agent);
        return Redirect('admin/travel_agent');
    }


    public function add_or_update(Request $request, $user, $travel_agent)
    {
        
        $validator = Validator::make(
            $request->all(),
            [
                'phone_no' => ['required', 'unique:users,phone_no,' . $user->id],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->all());
        }
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->adderss = $request->adderss;
        $user->phone_no = $request->phone_no;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->role_id = 4;
        if($request->password){
            $user->password =  Hash::make($request->password);
        }
        $user->save();
        $travel_agent->id = $request->id;
        $travel_agent->user_id = $user->id;
        $travel_agent->user_sale_agent_id = $request->user_sale_agent_id;
        $travel_agent->company_name = $request->company_name;
        $travel_agent->license_num = $request->license_num;
        $travel_agent->country = $request->country;
        $travel_agent->city = $request->city;
        $travel_agent->save();
            
        return $travel_agent;
    }

    public function destroy_undestroy($id)
    {
        $travel_agent = Travel_Agent::find($id);
        if ($travel_agent) {
            Travel_Agent::destroy($id);
            Users::destroy($travel_agent->user_id);
            $new_value = 'Activate';
        } else {
            Travel_Agent::withTrashed()->find($id)->restore();
            Users::withTrashed()->find($travel_agent->user_id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
}
