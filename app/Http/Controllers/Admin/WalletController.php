<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Handler\WalletHandler;
use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    
    public function index(Request $request)
    {
        return view('admin.wallet.index');
    }

    public function get_wallet(Request $request)
    {
        $walletHandler = new WalletHandler(); // Instantiate the WalletHandler class

        $userData = $walletHandler->user_balance_information();// Call the user_balance_information() method
        $response['data'] = $userData; 
        // echo json_encode($response);
        return $this->sendResponse(200,$response);
    }
}
