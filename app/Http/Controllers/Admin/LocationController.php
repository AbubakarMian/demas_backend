<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location_Type;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class LocationController extends Controller
{
    
    public function index(Request $request)
    {
        return view('admin.location.index');
    }

    public function get_location(Request $request)
    {
        $location = Locations::with('location_type')->orderBy('created_at', 'DESC')->select('*')->get();
        $locationData['data'] = $location;
        echo json_encode($locationData);
    }

    public function create()
    {
        $control = 'create';
        $location_type = Location_Type::pluck('name','id');
        return view('admin.location.create', compact('control','location_type'));
    }

    public function save(Request $request)
    {
        $location = new Locations();
        $this->add_or_update($request, $location);

        return redirect('admin/location');
    }
    public function edit($id)
    {
        $control = 'edit';
        $location = Locations::find($id);
        $location_type = Location_Type::pluck('name','id');
        return view('admin.location.create', compact(
            'control',
            'location',
            'location_type',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $location = Locations::find($id);
        $this->add_or_update($request, $location);
        return Redirect('admin/location');
    }


    public function add_or_update(Request $request, $location)
    {
        $location->location_type_id = $request->location_type_id;
        $location->name = $request->name;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->save();
        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $location = Locations::find($id);
        if ($location) {
            Locations::destroy($id);
            $new_value = 'Activate';
        } else {
            Locations::withTrashed()->find($id)->restore();
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
