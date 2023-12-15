<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Locations;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class JourneyController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.journey.index');
    }

    public function get_journey(Request $request)
    {
        $journey = Journey::orderBy('created_at', 'DESC')->select('*')->get();
        $journeyData['data'] = $journey;
        echo json_encode($journeyData);
    }

    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        $location = Locations::pluck('name','id');
        return view('admin.journey.create', compact('control','location'));
    }

    public function save(Request $request)
    {
        $journey = new journey();
        $this->add_or_update($request, $journey,true);

        return redirect('admin/journey');
    }
    public function edit($id)
    {
        $control = 'edit';
        $journey = Journey::find($id);
        $location = Locations::pluck('name','id');
        return view('admin.journey.create', compact(
            'control',
            'journey',
            'location',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $journey = Journey::find($id);
        $this->add_or_update($request, $journey,false);
        return Redirect('admin/journey');
    }


    public function add_or_update(Request $request, $journey,$add_journey_slot=true)
    {
        $location_pickup = Locations::find($request->pickup_location_id);
        $location_dropoff = Locations::find($request->dropoff_location_id);
        // $journey->name = $location_pickup->name.' to '.$location_dropoff->name;
        $journey->name = $request->name;
        $journey->pickup_location_id = $request->pickup_location_id;
        $journey->dropoff_location_id = $request->dropoff_location_id;

        $journey->save();
        if($add_journey_slot){
            $this->add_journey_slot($request,$journey);
        }

        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $journey = Journey::find($id);
        if ($journey) {
            Journey::destroy($id);
            $new_value = 'Activate';
        } else {
            Journey::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
    function add_journey_slot(Request $request, $journey){
        $slots = Slot::get();
        foreach ($slots as $key => $slot) {
            $journey_slot = new Journey_Slot();
            $journey_slot->slot_id = $slot->id;
            $journey_slot->journey_id = $journey->id;
            $journey_slot->save();
        }
    }
   
}
