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
        $this->add_or_update($request, $car);

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
        $this->add_or_update($request, $car);
        return Redirect('admin/car');
    }


    public function add_or_update(Request $request, $car)
    {
        // dd(json_encode($request->car_images_upload));
        $car->transport_type_id = $request->transport_type_id;
        // $car->user_owner_id = $request->user_owner_id;
        $car->details = $request->details;
        $car->images = $request->car_images_upload;
        $car->features = $request->features;
        $car->booking = $request->booking;
        $car->dontforget = $request->dontforget;
        $car->images = $request->car_images_upload;
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
    }
}