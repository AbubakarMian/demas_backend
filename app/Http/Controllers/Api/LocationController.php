<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function get_all(){
        $locations = Locations::paginate(500);
        $locations = $locations->items();
        return $this->sendResponse(200,$locations);
    }
}
