<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Sale_Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class SaltAgentController extends Controller
{

    public function index(Request $request)
    {
        // dd('hi');
     return view('admin.sale_agent.index');
    }

    public function get_sale_agent(Request $request)
    {
        
        // $sale_agent = Sale_Agent::with('user')->orderBy('created_at', 'DESC')->get();
        $sale_agent = Sale_Agent::with('user')->first();
        // $location = Locations::with('location_type')->first();
        // $sale_agent = Sale_Agent::with('user')->get();
        dd($location);
        // $sale_agentData['data'] = $sale_agent;
        // dd($sale_agent);
        // echo json_encode($sale_agentData);
    }


   
    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.sale_agent.create', compact('control', 
        // 'transport_type'
    ));
    }

    public function save(Request $request)
    {
        $sale_agent = new Sale_Agent();
        $user = new User();
        $this->add_or_update($request,$user ,$sale_agent);

        return redirect('admin/sale_agent');
    }
    public function edit($id)
    {
        $control = 'edit';
        $sale_agent = Sale_Agent::find($id);
        $user = User::find($id);

        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.sale_agent.create', compact(
            'control',
            'sale_agent',
            'user',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $sale_agent = Sale_Agent::find($id);
        $user = $sale_agent->user;
        // Sale_Agent::delete()
        $this->add_or_update($request, $user, $sale_agent);
        return Redirect('admin/sale_agent');
    }


    public function add_or_update(Request $request, $user, $sale_agent)
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
        $user->role_id = 3;
        $user->password =  Hash::make($request->password);
        // dd($user);
        $user->save();


        $sale_agent->id = $request->id;
        $sale_agent->user_id = $user->id;
        $sale_agent->save();
        

        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $sale_agent = Sale_Agent::find($id);
        if ($sale_agent) {
            Sale_Agent::destroy($id);
            $new_value = 'Activate';
        } else {
            Sale_Agent::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
