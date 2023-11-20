<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Locations;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function verify_journey(Request $request){
        
        try {
                $journey = Journey::where('pickup_location_id',$request->pickup_id)
                                    ->where('dropoff_location_id',$request->dropoff_id)
                                    ->first();
                if($journey){
                    return $this->sendResponse(200,$journey);
                }
                else{
                    $pickup = Locations::find($request->pickup_id);
                    $dropoff = Locations::find($request->dropoff_id);
                    return $this->sendResponse(500,null,
                    ['Journey from '.$pickup->name.' to '.$dropoff->name.' currently not avalible']);
                }
        } catch (\Exception $e) {
            return $this->sendResponse(
                500,
                null,
                [$e->getMessage()]
            );
        }
    }
}
