<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.car.index');
    }


    public function get_car(Request $request)
    {
        $car = Car::with('transport_type','driver.user')->orderBy('created_at', 'DESC')->select('*')->get();
        $carData['data'] = $car;
        echo json_encode($carData);
    }


   
    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.car.create', compact('control', 'transport_type'));
    }

    public function save(Request $request)
    {
        $car = new Car();
        return $this->add_or_update($request, $car);

        return redirect('admin/car');
    }
    public function edit($id)
    {
        $control = 'edit';
        $car = Car::find($id);
        // dd($car->images);
        $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.car.create', compact(
            'control',
            'car',
            'transport_type',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $car = car::find($id);
        // car::delete()
        return $this->add_or_update($request, $car);
        return Redirect('admin/car');
    }


    public function add_or_update(Request $request, $car)
    {
        // dd(json_encode($request->car_images_upload));
        $validator = Validator::make($request->all(),[
            'car_images_upload'=>'required'
        ],
        [
            'car_images_upload'=>[
                'required'=>'Images Required'
            ]
        ]
    );
        if($validator->fails()){
            return back()->with('error',$validator->messages());
        }
        $car->transport_type_id = $request->transport_type_id;
        $car->name = $request->name;
        $car->details = $request->details;
        $car->images = $request->car_images_upload;
        $car->features = $request->features;
        $car->number_plate = $request->number_plate;
        $car->owner_name = $request->owner_name;
        $car->seats = $request->seats;
        $car->luggage = $request->luggage;
        $car->doors = $request->doors;
        $car->booking = $request->booking;
        $car->dontforget = $request->dontforget;
        $car->save();
        return Redirect('admin/car');
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
    }
}