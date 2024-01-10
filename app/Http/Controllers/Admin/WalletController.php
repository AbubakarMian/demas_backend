<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    
    public function index(Request $request)
    {
        return view('admin.wallet.index');
    }

    public function get_user(Request $request)
    {
        $user = User::orderBy('created_at', 'DESC')->select('*')->get();
        $userData['data'] = $user;
        echo json_encode($userData);
    }

}
