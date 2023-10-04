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
            'title' => 'Users',
            'image' => "{{ asset('/images/car-2.png') }} ", // Add the image path for Module 1
        ];
        $modules[] = [

            'url' => 'admin/sale_agent',
            'title' => 'Sale Agents ',
             'image' => "{{ asset('/images/car-2.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/travel_agent',
            'title' => 'Travel Agents ',
             'image' => "{{ asset('/images/car-3.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/location',
            'title' => 'Locations ',
             'image' => "{{ asset('/images/car-4.png') }} ", // Add the image path for Module 1

        ];

        $modules[] = [

            'url' => 'admin/slot',
            'title' => ' Slots',
             'image' => "{{ asset('/images/car-5.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/journey',
            'title' => ' Journey',
             'image' => "{{ asset('/images/car-6.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/driver_journey',
            'title' => ' Driver Journey',
             'image' => "{{ asset('/images/car-7.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/car',
            'title' => 'Transports',
             'image' => "{{ asset('/images/car-8.png') }} ", // Add the image path for Module 1


        ];
        $modules[] = [

            'url' => 'admin/transport_type',
            'title' => 'Transport Type',
             'image' => "{{ asset('/images/car-1.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/price',
            'title' => 'Transport Prices',
             'image' => "{{ asset('/images/car-1.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/travel_agent_commission',
            'title' => 'Travel Agent Commissions',
             'image' => "{{ asset('/images/car-1.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/transport_journey_prices',
            'title' => 'Journey Prices',
             'image' => "{{ asset('/images/car-1.png') }} ", // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/driver',
            'title' => 'Driver',
            'image' => '/', // Add the image path for Module 1

        ];
        $modules[] = [

            'url' => 'admin/contactus',
            'title' => 'Contact Us',
            'image' => '/', // Add the image path for Module 1

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
