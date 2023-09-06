<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
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
        // $category = Category::pluck('name','id');
        return view('admin.journey.create', compact('control'));
    }

    public function save(Request $request)
    {
        $journey = new journey();
        $this->add_or_update($request, $journey);

        return redirect('admin/journey');
    }
    public function edit($id)
    {
        $control = 'edit';
        $journey = Journey::find($id);
        // $courses = Courses::pluck('full_name','id');
        // $category = Category::pluck('name','id');
        return view('admin.journey.create', compact(
            'control',
            'journey',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $journey = Journey::find($id);
        // Journey::delete()
        $this->add_or_update($request, $journey);
        return Redirect('admin/journey');
    }


    public function add_or_update(Request $request, $journey)
    {
        // dd($request->all());
        $journey->name = $request->pickup_location_id.$request->dropoff_location_id;
        $journey->pickup_location_id = $request->pickup_location_id;
        $journey->dropoff_location_id = $request->dropoff_location_id;

        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $journey->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $journey->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $journey->save();


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
    }}
