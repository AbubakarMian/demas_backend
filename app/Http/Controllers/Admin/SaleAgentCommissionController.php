<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\SaleAgent;
use App\Models\SalesAgentTripPrice;
use App\Models\Slot;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SaleAgentCommissionController extends Controller
{

    public function index()
    {
        $journey_list = Journey::pluck('name', 'id');
        $slot_list = Slot::pluck('name', 'id');
        $transport_type_list = Transport_Type::pluck('name', 'id');
        $sale_agent_list = SaleAgent::with('user_obj')->get()->pluck('user_obj.name', 'user_obj.id');
        return view('admin.sale_agent_comission.commsion.index', compact(
            'journey_list',
            'slot_list',
            'transport_type_list',
            'sale_agent_list',
        ));
    }
    public function get_commision_prices(Request $request)
    {
        $data = [];
        // $transport_types = Transport_Type::get();
        // $journies = Journey::get();
        // $slots = Slot::get();
        $transport_prices = TransportPrices::get();
        // dd(Config::get('constants.sales_agent.commission_types.agreed_trip_rate'));
        $sale_agents = SaleAgent::where('commision_type', Config::get('constants.sales_agent.commission_types.agreed_trip_rate'))->get();
        // 
        // $slot_arr = Journey_Slot::get();
        foreach ($transport_prices as $transport_price_key => $transport_price) {
            
            foreach ($sale_agents as $sale_agent_key => $sale_agent) {
                $transport_prices_obj = SalesAgentTripPrice::where('transport_price_id', $transport_price->id)
                    ->where('user_sale_agent_id', $sale_agent->user_id)
                    ->first(); // Use 'first' to retrieve a single record
                    
                if (!$transport_prices_obj) {
                    $transport_prices_obj = new SalesAgentTripPrice();
                    $transport_prices_obj->transport_price_id = $transport_price->id;
                    $transport_prices_obj->user_sale_agent_id = $sale_agent->user_id;
                    $transport_prices_obj->journey_id = $transport_price->journey_id;
                    $transport_prices_obj->slot_id = $transport_price->slot_id;
                    $transport_prices_obj->transport_type_id = $transport_price->transport_type_id;
                    $transport_prices_obj->is_default = $transport_price->is_default;
                    $transport_prices_obj->commission = 0;
                    $transport_prices_obj->price = $transport_price->price;
                    $transport_prices_obj->save();
                } 
            }
        }
        $sale_agent_commission = SalesAgentTripPrice::with([
            'journey', 'slot', 'transport_type', 'user_obj'
        ]);

        if ($request->journey_id) {
            $sale_agent_commission = $sale_agent_commission->where('journey_id', $request->journey_id);
        }
        if ($request->slot_id) {
            $sale_agent_commission = $sale_agent_commission->where('slot_id', $request->slot_id);
        }
        if ($request->transport_type_id) {
            $sale_agent_commission = $sale_agent_commission->where('transport_type_id', $request->transport_type_id);
        }
        if ($request->user_sale_agent_id) {
            $sale_agent_commission = $sale_agent_commission->where('user_sale_agent_id', $request->user_sale_agent_id);
        }

        $priceData['data'] = $sale_agent_commission->get();
        echo json_encode($priceData);
    }
    public function get_commision_prices_del(Request $request)
    {
        $data = [];
        $transport_types = Transport_Type::get();
        $journies = Journey::get();
        $slots = Slot::get();
        $sale_agents = SaleAgent::where('commision_type', Config::get('constants.commission_types.agreed_trip_rate'))->get();
        // 
        // $slot_arr = Journey_Slot::get();
        foreach ($slots as $slot_key => $slot) {
            foreach ($sale_agents as $sale_agent_key => $sale_agent) {
                foreach ($journies as $journey_key => $journey) {
                    foreach ($transport_types as $transport_type_key => $transport_type) {
                        $transport_type_id = $transport_type->id;
                        $transport_prices_obj = SalesAgentTripPrice::where('transport_type_id', $transport_type_id)
                            ->where('slot_id', $slot->id)
                            ->where('journey_id', $journey->id)
                            ->where('user_sale_agent_id', $sale_agent->user_id)
                            ->first(); // Use 'first' to retrieve a single record

                        if (!$transport_prices_obj) {
                            $transport_prices_obj = new SalesAgentTripPrice();
                            $transport_prices_obj->user_sale_agent_id = $sale_agent->user_id;
                            $transport_prices_obj->journey_id = $journey->id;
                            $transport_prices_obj->slot_id = $slot->id;
                            $transport_prices_obj->transport_type_id = $transport_type_id;
                            $transport_prices_obj->is_default = $slot->is_default;
                            $transport_prices_obj->commission = 0;
                            $transport_prices_obj->price = 0;
                            $transport_prices_obj->save();
                        } else {
                            // dd($data, $transport_prices_obj);
                        }
                    }
                }
            }
        }
        $sale_agent_commission = SalesAgentTripPrice::with([
            'journey', 'slot', 'transport_type', 'user_obj'
        ]);

        if ($request->journey_id) {
            $sale_agent_commission = $sale_agent_commission->where('journey_id', $request->journey_id);
        }
        if ($request->slot_id) {
            $sale_agent_commission = $sale_agent_commission->where('slot_id', $request->slot_id);
        }
        if ($request->transport_type_id) {
            $sale_agent_commission = $sale_agent_commission->where('transport_type_id', $request->transport_type_id);
        }
        if ($request->user_sale_agent_id) {
            $sale_agent_commission = $sale_agent_commission->where('user_sale_agent_id', $request->user_sale_agent_id);
        }

        $priceData['data'] = $sale_agent_commission->get();
        echo json_encode($priceData);
    }

    public function update_price(Request $request, $transport_Prices_id)
    {
        $Transport_Prices = SalesAgentTripPrice::find($transport_Prices_id);
        // $Transport_Prices->commission = $request->commission;
        $Transport_Prices->price = $request->price;
        $Transport_Prices->save();
        return $this->sendResponse(200, $Transport_Prices);
    }
}
