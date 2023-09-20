<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Travel_Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class TravelAgentController extends Controller
{

    public function index(Request $request)
    {
        // dd('hi');
     return view('admin.travel_agent.index');
    }

    public function get_travel_agent(Request $request)
    {
        
        $travel_agent = Travel_Agent::with('user_name')->orderBy('created_at', 'DESC')->get();
        // $travel_agent = Travel_Agent::with('user_name')->first();
        // $location = Locations::with('location_type')->first();
        // $travel_agent = Travel_Agent::with('user')->get();
        // dd($location);
        $travel_agentData['data'] = $travel_agent;
        // dd($travel_agent);
        echo json_encode($travel_agentData);
    }


   
    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.travel_agent.create', compact('control', 
        // 'transport_type'
    ));
    }

    public function save(Request $request)
    {
        $travel_agent = new Travel_Agent();
        $user = new User();
        $this->add_or_update($request,$user ,$travel_agent);

        return redirect('admin/travel_agent');
    }
    public function edit($id)
    {
        $control = 'edit';
        $travel_agent = Travel_Agent::find($id);
        $user = User::find($id);

        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.travel_agent.create', compact(
            'control',
            'travel_agent',
            'user',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $travel_agent = Travel_Agent::find($id);
        $user = $travel_agent->user;
        // Travel_Agent::delete()
        $this->add_or_update($request, $user, $travel_agent);
        return Redirect('admin/travel_agent');
    }


    public function add_or_update(Request $request, $user, $travel_agent)
    {
        // dd($request->all());
        // dd($user);
        
        
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->phone_no = $request->phone_no;
        $user->city = $request->city;
        $user->role_id = 4;
        $user->password =  Hash::make($request->password);
        // dd($user);
        $user->save();


        $travel_agent->id = $request->id;
        $travel_agent->user_id = $user->id;
        $travel_agent->save();
        

        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $travel_agent = Travel_Agent::find($id);
        if ($travel_agent) {
            Travel_Agent::destroy($id);
            $new_value = 'Activate';
        } else {
            Travel_Agent::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
