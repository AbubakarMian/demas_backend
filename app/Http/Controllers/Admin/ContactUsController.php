<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.contactus.index');
    }

    public function get_contactus(Request $request)
    {
        $contactus = ContactUs::orderBy('created_at', 'DESC')->get();
        $contactusData['data'] = $contactus;
        echo json_encode($contactusData);
    }
}
