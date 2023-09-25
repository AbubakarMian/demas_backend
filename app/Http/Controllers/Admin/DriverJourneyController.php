<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverJourney;
use App\Models\Journey;
use App\Models\Journey_Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class DriverJourneyController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.driver_journey.index');
    }

    public function get_driver_journey(Request $request)
    {
        $driver_journey = DriverJourney::with('journey','journey_slot')->orderBy('created_at', 'DESC')->get();
        $driver_journeyData['data'] = $driver_journey;
        echo json_encode($driver_journeyData);
    }

    public function create()
    {
        $control = 'create';
        $journey = Journey::pluck('name','id');
        $driver = Driver::pluck('id');
        $journey_slot = Journey_Slot::pluck('id');
        return view('admin.driver_journey.create', compact(
            'control',
            'journey',
            'driver',
            'journey_slot',
        ));
    }

    public function save(Request $request)
    {
        $driver_journey = new DriverJourney();
        $this->add_or_update($request, $driver_journey);

        return redirect('admin/driver_journey');
    }
    public function edit($id)
    {
        $control = 'edit';
        $driver_journey = DriverJourney::find($id);
        $journey = Journey::pluck('name','id');
        $driver = Driver::pluck('id');
        $journey_slot = Journey_Slot::pluck('id');
        return view('admin.driver_journey.create', compact(
            'control',
            'driver_journey',
            'journey',
            'driver',
            'journey_slot',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $driver_journey = DriverJourney::find($id);
        // DriverJourney::delete()
        $this->add_or_update($request, $driver_journey);
        return Redirect('admin/driver_journey');
    }


    public function add_or_update(Request $request, $driver_journey)
    {
        // dd($request->all());
        $driver_journey->user_driver_id = $request->user_driver_id;
        $driver_journey->journey_id = $request->journey_id;
        $driver_journey->journey_slot_id = $request->journey_slot_id;
        $driver_journey->rate = $request->rate;
        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $driver_journey->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $driver_journey->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $driver_journey->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $driver_journey = DriverJourney::find($id);
        if ($driver_journey) {
            DriverJourney::destroy($id);
            $new_value = 'Activate';
        } else {
            DriverJourney::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
