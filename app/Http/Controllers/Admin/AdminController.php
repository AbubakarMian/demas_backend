<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Product;
use app\User;



class AdminController extends Controller
{

    function index()
    {
        return view('login.login');
    }


    function checklogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password'  => 'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'email'  => $request->get('email'),
            'password' => $request->get('password'),
            // 'role_id' => 1
        );

        if(Auth::attempt($user_data))
        {
            session(['my_timezone' => $request->my_timezone]);
            return redirect('admin/dashboard');
        }
        // elseif()
        // {
        //     return back()->with('error', 'Wrong Login Details');
        // }

        else
        {
            return back()->with('error', 'Wrong Login Details');
        }

    }



    function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }


    function dashboard (){

        $admin_common = new \stdClass();
        $admin_dashboard = $this->admin_dashboard();

        $modules = $admin_dashboard['modules'];
        $reports = $admin_dashboard['reports'];
        $admin_common->id = '1';
        $admin_common->modules = $modules;
        $admin_common->reports = $reports;
        $admin_common->name = 'Admin';

        $chart = $admin_dashboard['chart'];

        session(['admin_common' => $admin_common]);
        return view('layouts.default_dashboard',compact(
            'chart'));
    }
    public function admin_dashboard()
    {
        $modules[] = [

            'url' => 'admin/user',
            'title' => 'Users ',

        ];
        $modules[] = [

            'url' => 'admin/location',
            'title' => 'Locations ',

        ];

        $modules[] = [

            'url' => 'admin/journey_slot',
            'title' => ' Journey Slot ',

        ];
        $modules[] = [

            'url' => 'admin/journey',
            'title' => ' Journey',

        ];
        $modules[] = [

            'url' => 'admin/driver_journey',
            'title' => ' Driver Journey',

        ];
        $modules[] = [

            'url' => 'admin/car',
            'title' => 'Car',

        ];
        $modules[] = [

            'url' => 'admin/transport_type',
            'title' => 'Transport Type',

        ];
        $modules[] = [

            'url' => 'admin/price',
            'title' => 'Transport Prices',

        ];
         
        $reports=[];
        // $reports[] = [

        //     'url' => 'admin/course_register',
        //     'title' => 'Course Register ',

        // ];
       

        $myvar = [];
        $myvar['modules'] = $modules;
        $myvar['reports'] = $reports ;
        $myvar['chart'] = [];

        return $myvar;
    }
}
