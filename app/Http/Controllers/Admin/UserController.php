<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.user.index');
    }

    public function get_user(Request $request)
    {
        $user = User::orderBy('created_at', 'DESC')->select('*')->get();
        $userData['data'] = $user;
        echo json_encode($userData);
    }

    public function create()
    {
        $control = 'create';
        $role = Role::pluck('name','id');
        // $category = Category::pluck('name','id');
        return view('admin.user.create', compact('control',
    'role'
    ));
    }

    public function save(Request $request)
    {
        $user = new User();
        $this->add_or_update($request, $user);

        return redirect('admin/user');
    }
    public function edit($id)
    {
        $control = 'edit';
        $user = User::find($id);
        $role = Role::pluck('name','id');
        // $category = Category::pluck('name','id');
        return view('admin.user.create', compact(
            'control',
            'user',
            'role',
           
        ));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        // user::delete()
        $this->add_or_update($request, $user);
        return Redirect('admin/user');
    }


    public function add_or_update(Request $request, $user)
    {
        // dd($request->all());
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->adderss = $request->adderss;
        $user->phone_no = $request->phone_no;
        $user->role_id = $request->role_id;
        $user->password =  Hash::make($request->password);


        // if($request->hasFile('upload_book')){

        //     $file =$request->upload_book;
        //     $filename = $file->getClientOriginalName();

        //     $path = public_path().'/uploads/';
        //     $u  =  $file->move($path, $filename);

        //     $db_path_save_book = asset('/uploads/'.$filename);
        //     $user->upload_book =  $db_path_save_book;
        // }
        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->avatar;
        //     $root = $request->root();
        //     $user->avatar = $this->move_img_get_path($avatar, $root, 'image');
        // }
        $user->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $user = User::find($id);
        if ($user) {
            User::destroy($id);
            $new_value = 'Activate';
        } else {
            User::withTrashed()->find($id)->restore();
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
