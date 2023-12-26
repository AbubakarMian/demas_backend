<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewAgent;
use App\Models\SaleAgent;
use App\Models\Travel_Agent;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class NewAgentController extends Controller
{
    public function index()
    {
        return view('admin.new_agent.index');
    }
    public function get_new_agent($id = 0)
    {
        $user = NewAgent::orderby('id', 'asc')->select('*')->get();
        $userData['data'] = $user;

        echo json_encode($userData);
    }
    // public function create()
    // {
    //     $control = 'create';
    //     // $journey = Journey::pluck('name', 'id');
    //     return view('new_agent.index', compact(
    //         'control',
    //         // 'journey'
    //     ));
    // }

    public function create($new_agent_id)
    {
        $control = 'edit';
        $new_agent = NewAgent::find($new_agent_id);
        $travel_agent = new Travel_Agent();
        // $user_obj = new Users();
        $user_obj = $new_agent;
        $travel_agent->id = 0;
        $travel_agent->user_obj = $user_obj;   
        $user = $user_obj;
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

    public function save(Request $request)
    {
        $new_agent = new NewAgent();
        $user = new User();
        $travel_agent = new Travel_Agent();

        $this->add_or_update(
            $request,
            $new_agent,
            $travel_agent,
            $user
        );

        // Save the user model
        $user->save();

        // Assign the user ID to travel_agent
        $travel_agent->user_id = $user->id;

        // Save the travel_agent model
        $travel_agent->save();

        return redirect('admin/$new_agent');
    }
    public function edit($id)
    {
        $control = 'edit';
        $new_agent = NewAgent::find($id);
        $travel_agent = Travel_Agent::find($id);
        // $journey = Journey::pluck('name', 'id');
        return view(
            'admin.$new_agent.create',
            compact(
                'control',
                '$new_agent',
                '$travel_agent',
                // 'journey',

            )
        );
    }

    public function update(Request $request, $id)
    {
        $new_agent = NewAgent::find($id);
        $user = User::find($id);
        $travel_agent = Travel_Agent::find($id);

        $this->add_or_update(
            $request,
            $new_agent,
            $travel_agent,
            $user
        );

        // Save the user model
        $user->save();

        // Assign the user ID to travel_agent
        $travel_agent->user_id = $user->id;

        // Save the travel_agent model
        $travel_agent->save();

        return Redirect('admin/$new_agent');
    }

    public function add_or_update(Request $request, $new_agent, $user, $travel_agent)
    {

        $user->name = $new_agent->name;
        $user->last_name = $new_agent->last_name;
        $user->email = $new_agent->email;
        $user->adderss = $new_agent->adderss;
        $user->phone_no = $new_agent->phone_no;
        $user->whatsapp_number = $new_agent->whatsapp_number;
        $user->role_id = 4;
        if ($request->password) {
            $user->password =  Hash::make($new_agent->password);
        }



        $travel_agent->id = $new_agent->id;
        $travel_agent->user_id = $user->id;

        $travel_agent->save();
        return redirect()->back();
    }
    public function destroy_undestroy($id)
    {
        $new_agent = NewAgent::find($id);
        if ($new_agent) {
            NewAgent::destroy($id);
            $new_value = 'Activate';
        } else {
            NewAgent::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
};
