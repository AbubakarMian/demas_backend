<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.driver.index');
    }

    public function get_driver(Request $request)
    {
        $drivers = Driver::with('user_obj')->whereHas('user_obj',function($q){
            $q->where('role_id',5);
        })->orderBy('created_at', 'DESC')->get();
        $driverData['data'] = $drivers;
        echo json_encode($driverData);
    }



    public function create()
    {
        $control = 'create';
        $driver_categories = Config::get('constants.driver.categories');

        return view('admin.driver.create', compact(
            'control',
            'driver_categories'
        ));
    }

    public function save(Request $request)
    {
        $driver = new Driver();
        $user = new User();
        return $this->add_or_update($request, $user, $driver);
    }
    public function edit($id)
    {
        $control = 'edit';
        $driver = Driver::find($id);
        $driver_categories = Config::get('constants.driver.categories');

        return view(
            'admin.driver.create',
            compact(
                'control',
                'driver',
                'driver_categories'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);
        $user = $driver->user_obj;
        return $this->add_or_update($request, $user, $driver);
        // return Redirect('admin/driver');
    }


    public function add_or_update(Request $request, $user, $driver)
    {
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
        $user->city = $request->city;
        $user->state = $request->state;
        $user->phone_no = $request->phone_no;
        $user->city = $request->city;
        $user->adderss = $request->adderss;
        $user->whatsapp_number = $request->whatsapp_number;
        $user->role_id = 5;
        if ($request->password) {
            $user->password =  Hash::make($request->password);
        }
        $user->save();
        $driver->id = $request->id;
        $driver->user_id = $user->id;
        $driver->driver_category = $request->driver_category;
        // $driver->commision_type = $request->commision_type;
        $driver->iqama_number = $request->iqama_number;
        $driver->commision = $request->commision;

        if($request->driver_category == 'own'){
            $driver->commision_type = Config::get('constants.driver.commission_types_keys.own');
        }
        else{//out_source
            $driver->commision_type = Config::get('constants.driver.commission_types_keys.per_trip');
        }
        $driver->save();
        return Redirect('admin/driver');
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
    }
}
