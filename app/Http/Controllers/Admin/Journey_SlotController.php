<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey_Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class Journey_SlotController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.journey_slot.index');
    }

    public function get_journey_slot(Request $request)
    {
        $journey_slot = Journey_Slot::orderBy('created_at', 'DESC')->select('*')->get();
        $journey_slotData['data'] = $journey_slot;
        echo json_encode($journey_slotData);
    }

    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $category = Category::pluck('name','id');
        return view('admin.journey_slot.create', compact('control'));
    }

    public function save(Request $request)
    {
        $journey_slot = new journey_slot();
        $this->add_or_update($request, $journey_slot);

        return redirect('admin/journey_slot');
    }
    public function edit($id)
    {
        $control = 'edit';
        $journey_slot = Journey_Slot::find($id);
        // $courses = Courses::pluck('full_name','id');
        // $category = Category::pluck('name','id');
        return view('admin.journey_slot.create', compact(
            'control',
            'journey_slot',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $journey_slot = Journey_Slot::find($id);
        // Journey_Slot::delete()
        $this->add_or_update($request, $journey_slot);
        return Redirect('admin/journey_slot');
    }


    public function add_or_update(Request $request, $journey_slot)
    {
        // dd($request->all());
        $journey_slot->journey_id = $request->journey_id;
        $journey_slot->from_date = $request->from_date;
        $journey_slot->to_date = $request->to_date;
    


        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $journey_slot->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $journey_slot->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $journey_slot->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $journey_slot = Journey_Slot::find($id);
        if ($journey_slot) {
            Journey_Slot::destroy($id);
            $new_value = 'Activate';
        } else {
            Journey_Slot::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
