<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Sale_Agent;
use App\Models\SaleAgent;
use App\Models\Travel_Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SaltAgentController extends Controller
{

    public function index(Request $request)
    {
        // dd('hi');
        return view('admin.sale_agent.index');
    }

    public function get_sale_agent(Request $request)
    {

        $sale_agent = SaleAgent::with('user_obj')->orderBy('created_at', 'DESC')->get();
        // $sale_agent = SaleAgent::with('user_name')->first();
        // $location = Locations::with('location_type')->first();
        // $sale_agent = SaleAgent::with('user')->get();
        // dd($location);
        $sale_agentData['data'] = $sale_agent;
        // dd($sale_agent);
        echo json_encode($sale_agentData);
    }



    public function create()
    {
        $control = 'create';
        $travel_agent = Travel_Agent::with('user_obj')->pluck('id');
        $commission_types = Config::get('constants.driver.commission_types');

        return view('admin.sale_agent.create', compact('control',
         'travel_agent',
         'commission_types',
        ));
    }

    public function save(Request $request)
    {
        $sale_agent = new SaleAgent();
        $user = new User();
        $this->add_or_update($request, $user, $sale_agent);

        return redirect('admin/sale_agent');
    }
    public function edit($id)
    {
        $control = 'edit';
        $sale_agent = SaleAgent::find($id);
        $user = User::find($id);
        $commission_types = Config::get('constants.driver.commission_types');
        $travel_agent = Travel_Agent::with('user_obj')->pluck('id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view(
            'admin.sale_agent.create',
            compact(
                'control',
                'sale_agent',
                'travel_agent',
                'user',
                'commission_types',

            )
        );
    }

    public function update(Request $request, $id)
    {
        $sale_agent = SaleAgent::find($id);
        $user = $sale_agent->user;
        // SaleAgent::delete()
        $this->add_or_update($request, $user, $sale_agent);
        // return Redirect('admin/sale_agent');
    }


    public function add_or_update(Request $request, $user, $sale_agent)
    {
        // dd($request->all());
        // dd($user);

        $validator = Validator::make(
            $request->all(),
            [
                'phone_no' => ['required', 'unique:users,phone_no,' . $user->id],
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            ]
        );


        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages());
        }
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->adderss = $request->adderss;
        $user->phone_no = $request->phone_no;
        $user->role_id = 3;
        if ($request->password) {
            $user->password =  Hash::make($request->password);
        }        // dd($user);
        $user->save();


        $sale_agent->id = $request->id;
        $sale_agent->user_id = $user->id;
        $sale_agent->commision_type = $request->commision_type;
        $sale_agent->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $sale_agent = SaleAgent::find($id);
        if ($sale_agent) {
            SaleAgent::destroy($id);
            $new_value = 'Activate';
        } else {
            SaleAgent::withTrashed()->find($id)->restore();
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