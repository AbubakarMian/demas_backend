<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Slot;
use App\Models\Transport_Type;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use Illuminate\Http\Request;


class TravelAgentCommissionController extends Controller
{
    public function index()
    {
        $journey_list = Journey::pluck('name','id');
        $slot_list = Slot::pluck('name','id');
        $transport_type_list = Transport_Type::pluck('name','id');
        $travel_agent_list = Travel_Agent::with('user_obj')->get()->pluck('user_obj.name','id');
        return view('admin.travel_agent.commsion.index',compact(
            'journey_list',
            'slot_list',
            'transport_type_list',
            'travel_agent_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        $data = [];
        $transport_types = Transport_Type::get();
        $journies = Journey::get();
        $slots = Slot::get();
        $travel_agents = Travel_Agent::get();
        // $slot_arr = Journey_Slot::get();
        foreach ($slots as $slot_key => $slot) {
            foreach ($travel_agents as $travel_agent_key => $travel_agent) {
                foreach ($journies as $journey_key => $journey) {
                    foreach ($transport_types as $transport_type_key => $transport_type) {
                        $transport_type_id = $transport_type->id;
                        $transport_prices_obj = TravelAgentCommission::where('transport_type_id', $transport_type_id)
                            ->where('slot_id', $slot->id)
                            ->where('journey_id', $journey->id)
                            ->first(); // Use 'first' to retrieve a single record

                        if (!$transport_prices_obj) {
                            $transport_prices_obj = new TravelAgentCommission();
                            $transport_prices_obj->user_travel_agent_id = $travel_agent->user_id;
                            $transport_prices_obj->journey_id = $journey->id;
                            $transport_prices_obj->slot_id = $slot->id;
                            $transport_prices_obj->transport_type_id = $transport_type_id;
                            $transport_prices_obj->is_default = $slot->is_default;
                            $transport_prices_obj->commission = 0;
                            $transport_prices_obj->save();
                        } else {
                            // dd($data, $transport_prices_obj);
                        }
                    }
                }
            }
        }
        $travel_agent_commission = TravelAgentCommission::with([
            'journey', 'slot', 'transport_type','user_obj'
        ]);

        if($request->journey_id){
            $travel_agent_commission = $travel_agent_commission->where('journey_id',$request->journey_id);
        }
        if($request->slot_id){
            $travel_agent_commission = $travel_agent_commission->where('slot_id',$request->slot_id);
        }
        if($request->transport_type_id){
            $travel_agent_commission = $travel_agent_commission->where('transport_type_id',$request->transport_type_id);
        }
        if($request->agent_id){
            $travel_agent_commission = $travel_agent_commission->where('user_travel_agent_id',$request->agent_id);
        }

        $priceData['data'] = $travel_agent_commission->get();
        echo json_encode($priceData);
    }

    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = TravelAgentCommission::find($transport_Prices_id);
        $Transport_Prices->commission = $request->commission;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }
}
