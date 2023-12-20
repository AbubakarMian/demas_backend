<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\Journey_Slot;
use App\Models\Slot;
use App\Models\Transport_Type;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelAgentCommissionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $journey_list = Journey::pluck('name','id');
        $slot_list = Slot::pluck('name','id');
        $transport_type_list = Transport_Type::pluck('name','id');
        $travel_agent_list = Travel_Agent::with('user_obj')->get()->pluck('user_obj.name','user_obj.id');
        return view('admin.travel_agent.commsion.index',compact(
            'user',
            'journey_list',
            'slot_list',
            'transport_type_list',
            'travel_agent_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        $user = Auth::user();
        $travel_agent_commission = $this->get_travel_agent_commission($request);
        $priceData['user'] = $user;
        $priceData['data'] = $travel_agent_commission;
        echo json_encode($priceData);
    }

    public function get_travel_agent_commission(Request $request){

        $user = Auth::user();
        $user_obj = Users::with('sale_agent','travel_agent')->find($user->id);
        $travel_agent_commission = TravelAgentCommission::with([
            'journey',
            'slot',
            'transport_type',
            'user_obj',
            'travel_agent',
            'sale_agent_commission'=>function($q)use($user_obj){
                $q->join('sale_agent', 'sale_agent.user_id', '=', 'sales_agent_trip_price.user_sale_agent_id');
            }
        ]);

        if($user_obj->sale_agent){
            $travel_agent_commission = $travel_agent_commission->wherehas('travel_agent',function($q)use($user_obj){
                $q->where('travel_agent.user_sale_agent_id',$user_obj->id);
            });
        }

        if($user_obj->travel_agent){
            $travel_agent_commission = $travel_agent_commission->where('user_travel_agent_id',$user_obj->id);
        }

        if($request->journey_id){
            $travel_agent_commission = $travel_agent_commission->where('journey_id',$request->journey_id);
        }
        if($request->slot_id){
            $travel_agent_commission = $travel_agent_commission->where('slot_id',$request->slot_id);
        }
        if($request->transport_type_id){
            $travel_agent_commission = $travel_agent_commission->where('transport_type_id',$request->transport_type_id);
        }
        if($request->user_travel_agent_id){
            $travel_agent_commission = $travel_agent_commission->where('user_travel_agent_id',$request->user_travel_agent_id);
        }
        
        $travel_agent_commission = $travel_agent_commission->get();
        $travel_agent_commission->transform(function($travel_agent_commission_item)use($user_obj){
            $sale_agent_commission_obj = null;
            if(isset($travel_agent_commission_item->sale_agent_commission[0])){
                $sale_agent_commission_obj = $travel_agent_commission_item->sale_agent_commission[0];
            }
            $travel_agent_commission_item->sale_agent_commission_obj = $sale_agent_commission_obj;
            if($user_obj->travel_agent){
                $travel_agent_commission_item->sale_agent_commission_obj->price = '-';
                unset($travel_agent_commission_item->sale_agent_commission_obj->price);
            }
            unset($travel_agent_commission_item->sale_agent_commission);
            return $travel_agent_commission_item;
        });

        return $travel_agent_commission;
    }
    public function update_price(Request $request, $transport_Prices_id)
    {
        $user = Auth::user();
        $Transport_Prices = TravelAgentCommission::with('travel_agent')->find($transport_Prices_id);
        if($Transport_Prices->travel_agent->user_sale_agent_id == $user->id){
            $Transport_Prices->price = $request->price;
            $Transport_Prices->save();
        }
        return $this->sendResponse(200, $Transport_Prices);

    }
}
