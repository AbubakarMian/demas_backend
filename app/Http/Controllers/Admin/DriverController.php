<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class DriverController extends Controller
{

    public function index(Request $request)
    {
        // dd('hi');
     return view('admin.driver.index');
    }

    public function get_driver(Request $request)
    {
        
        $driver = Driver::with('user_obj')->orderBy('created_at', 'DESC')->get();
        // $driver = Driver::with('user_name')->first();
        // $location = Locations::with('location_type')->first();
        // $driver = Driver::with('user')->get();
        // dd($location);
        $driverData['data'] = $driver;
        // dd($driver);
        echo json_encode($driverData);
    }


   
    public function create()
    {
        $control = 'create';
// $travel_agents = Travel_Agent::with('user_name')->pluck( 'id');
        return view('admin.driver.create', compact('control',
        //  'travel_agents'
        ));
    }

    public function save(Request $request)
    {
        $driver = new Driver();
        $user = new User();
        $this->add_or_update($request , $user ,$driver);

        return redirect('admin/driver');
    }
    public function edit($id)
    {
        $control = 'edit';
        $driver = Driver::find($id);
        $user = User::find($id);

        // $travel_agents = Travel_Agent::with('user_name')->pluck('id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.driver.create', compact(
            'control',
            'driver',
            // 'travel_agents',
            'user',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);
        $user = $driver->user;
        // Driver::delete()
        $this->add_or_update($request, $user, $driver);
        return Redirect('admin/driver');
    }


    public function add_or_update(Request $request, $user, $driver)
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


        $driver->id = $request->id;
        $driver->user_id = $user->id;
        $driver->save();
        

        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            Driver::destroy($id);
            $new_value = 'Activate';
        } else {
            Driver::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
