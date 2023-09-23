<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use File;

class CommonServicesController extends Controller
{
    public function crop_image(Request $request)
    {
        if(isset($request->pre_image)){
            File::delete($request->pre_image);
        }
        $folderPath = public_path('images/');
        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = uniqid() . '.png';
        $imageFullPath = $folderPath . $imageName;
        $su = file_put_contents($imageFullPath, $image_base64);
        $image_path = asset('/images/' . $imageName);
        return response()->json(['status'=>true,'success' => 'Crop Image Uploaded Successfully', 'image' => $image_path]);
    }
}
