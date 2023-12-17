<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\TripCommissionHandler;
use App\Models\Locations;
use App\Models\Sale_Agent;
use App\Models\SaleAgent;
use App\Models\Travel_Agent;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SaltAgentController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.sale_agent.index');
    }

    public function get_sale_agent(Request $request)
    {
        $sale_agent = SaleAgent::with('user_obj')->orderBy('created_at', 'DESC')->get();
        $sale_agentData['data'] = $sale_agent;
        echo json_encode($sale_agentData);
    }

    public function create()
    {
        $control = 'create';
        $travel_agent = Travel_Agent::with('user_obj')->pluck('id');
        $commission_types = Config::get('constants.sales_agent.commission_lables');

        return view('admin.sale_agent.create', compact(
            'control',
            'travel_agent',
            'commission_types',
        ));
    }

    public function save(Request $request)
    {
        $sale_agent = new SaleAgent();
        $user = new User();
        return $this->add_or_update($request, $user, $sale_agent);
    }
    public function edit($id)
    {
        $control = 'edit';
        $sale_agent = SaleAgent::find($id);
        $user = $sale_agent->user_obj;
        $commission_types = Config::get('constants.sales_agent.commission_lables');
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
        $user = $sale_agent->user_obj;
        return $this->add_or_update($request, $user, $sale_agent);
        // return Redirect('admin/sale_agent');
    }


    public function add_or_update(Request $request, $user, $sale_agent)
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
        if ($request->commision > 99 && in_array($request->commision_type, ['profit_percent', 'sales_percent'])) {
            return redirect()->back()->with('error', ['Commission can not be greater that 99%']);
        }
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->adderss = $request->adderss;
        $user->phone_no = $request->phone_no;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->role_id = 3;
        if ($request->password) {
            $user->password =  Hash::make($request->password);
        }
        $user->save();
        $sale_agent->id = $request->id;
        $sale_agent->user_id = $user->id;
        $sale_agent->commision_type = $request->commision_type;
        $sale_agent->commision = $request->commision;
        $sale_agent->save();

        if($request->commision_type == Config::get('constants.sales_agent.commission_types.agreed_trip_rate')){
            $trip_commission_handler = new TripCommissionHandler();
            $trip_commission_handler->create_sale_agent_trip_prices([],[$sale_agent]);
        }

        return redirect('admin/sale_agent');
    }

    public function destroy_undestroy($id)
    {
        $sale_agent = SaleAgent::find($id);
        $sale_agent->active = 0;
        $sale_agent->save();
        if ($sale_agent) {
            $sale_agent->active = 0;
            $sale_agent->save();
            SaleAgent::destroy($id);
            Users::destroy($sale_agent->user_id);
            $new_value = 'In Active';
        } else {
            SaleAgent::withTrashed()->find($id)->restore();
            $sale_agent = SaleAgent::find($id);
            $sale_agent->active = 1;
            $sale_agent->save();
            Users::withTrashed()->find($sale_agent->user_id)->restore();
            $new_value = 'Active';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }

    public function active_inactive($id)
    {
        $sale_agent = SaleAgent::withTrashed()->find($id);
        $sale_agent->active = $sale_agent->active ? 0 : 1;
        $sale_agent->save();
        $new_value = $sale_agent->active ? 'Inactive':'Activate';
        // $response = Response::json([
        //     "status" => true,
        //     'action' => Config::get('constants.ajax_action.update'),
        //     'new_value' => $new_value
        // ]);
        $res = new \stdClass();
        $res->new_value = $new_value;
        $res->updated_obj = $sale_agent;
        $response = $this->sendResponse(200,$res);
        return $response;
    }
}
