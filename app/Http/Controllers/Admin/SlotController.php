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

class SlotController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.slot.index');
    }

    public function get_slot(Request $request)
    {
        $slot = Slot::where('is_default',0)->orderBy('created_at', 'DESC')->select('*')->get();
        $slotData['data'] = $slot;
        echo json_encode($slotData);
    }

    public function create()
    {
        $control = 'create';
        $journey = Journey::pluck('name', 'id');
        return view('admin.slot.create', compact('control', 'journey'));
    }

    public function save(Request $request)
    {
        $slot = new Slot();
        $this->add_or_update($request, $slot);

        return redirect('admin/slot');
    }
    public function edit($id)
    {
        $control = 'edit';
        $slot = Slot::find($id);
        $journey = Journey::pluck('name', 'id');
        return view(
            'admin.slot.create',
            compact(
                'control',
                'slot',
                'journey',

            )
        );
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::find($id);
        $this->add_or_update($request, $slot);
        return Redirect('admin/slot');
    }


    public function add_or_update(Request $request, $slot)
    {
        $from_date_timestamp = strtotime($request->from_date);
        $to_date_timestamp = strtotime($request->to_date);
        if ($request->name) {
            $slot->name = $request->name;
        } else {
            $slot->name = $request->from_date . ' to ' . $request->to_date;
        }
        $slot->start_date = $from_date_timestamp;
        $slot->end_date = $to_date_timestamp;
        $slot->save();
        $this->add_journey_slot($request, $slot);

        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $slot = Slot::find($id);
        if ($slot) {
            Slot::destroy($id);
            $new_value = 'Activate';
        } else {
            Slot::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
    function add_journey_slot(Request $request, $slot)
    {
        $journeies = Journey::get();
        foreach ($journeies as $key => $journey) {
            $journey_slot = new Journey_Slot();
            $journey_slot->slot_id = $slot->id;
            $journey_slot->journey_id = $journey->id;
            $journey_slot->save();
        }
    }
}
