<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Transport_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;


class CarController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.car.index');
    }

    public function get_car(Request $request)
    {
        $car = Car::with('transport_type')->orderBy('created_at', 'DESC')->select('*')->get();
        $carData['data'] = $car;
        echo json_encode($carData);
    }

    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        $transport_type = Transport_Type::pluck('name','id');
        return view('admin.car.create', compact('control','transport_type'));
    }

    public function save(Request $request)
    {
        $car = new Car();
        $this->add_or_update($request, $car);

        return redirect('admin/car');
    }
    public function edit($id)
    {
        $control = 'edit';
        $car = car::find($id);
        // $courses = Courses::pluck('full_name','id');
        $transport_type = Transport_Type::pluck('name','id');
        return view('admin.car.create', compact(
            'control',
            'car',
            'transport_type',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $car = car::find($id);
        // car::delete()
        $this->add_or_update($request, $car);
        return Redirect('admin/car');
    }


    public function add_or_update(Request $request, $car)
    {
        // dd($request->all());
        $car->transport_type_id = $request->transport_type_id;
        $car->user_owner_id = $request->user_owner_id;
        $car->details = $request->details;


        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $car->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $car->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $car->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $car = car::find($id);
        if ($car) {
            car::destroy($id);
            $new_value = 'Activate';
        } else {
            car::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
