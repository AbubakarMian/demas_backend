<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transport_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;


class TransportTypeController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.transport_type.index');
    }

    public function get_transport_type(Request $request)
    {
        $transport_type = Transport_Type::orderBy('created_at', 'DESC')->select('*')->get();
        $transport_typeData['data'] = $transport_type;
        echo json_encode($transport_typeData);
    }

    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $category = Category::pluck('name','id');
        return view('admin.transport_type.create', compact('control'));
    }

    public function save(Request $request)
    {
        $transport_type = new Transport_Type();
        $this->add_or_update($request, $transport_type);

        return redirect('admin/transport_type');
    }
    public function edit($id)
    {
        $control = 'edit';
        $transport_type = Transport_Type::find($id);
        // $courses = Courses::pluck('full_name','id');
        // $category = Category::pluck('name','id');
        return view('admin.transport_type.create', compact(
            'control',
            'transport_type',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $transport_type = Transport_Type::find($id);
        // Transport_Type::delete()
        $this->add_or_update($request, $transport_type);
        return Redirect('admin/transport_type');
    }


    public function add_or_update(Request $request, $transport_type)
    {
        // dd($request->all());
        $transport_type->name = $request->name;
        $transport_type->seats = $request->seats;
        $transport_type->luggage = $request->luggage;
        $transport_type->passenger = $request->passenger;

        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $transport_type->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $transport_type->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $transport_type->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $transport_type = Transport_Type::find($id);
        if ($transport_type) {
            Transport_Type::destroy($id);
            $new_value = 'Activate';
        } else {
            Transport_Type::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }}
