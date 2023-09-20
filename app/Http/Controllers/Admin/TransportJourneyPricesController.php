<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransportJourneyPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class TransportJourneyPricesController extends Controller
{

    public function index(Request $request)
    {
        return view('admin.transport_journey_prices.index');
    }


    public function get_transport_journey_prices(Request $request)
    {
        $transport_journey_prices = TransportJourneyPrices::with(
            'journeyslot.slot',
            // 'tripprice',
            // 'saleagent',
            // 'saleagent_com',
            // 'travelagent',
            // 'travelagent_com',
            // 'driver_com',
            )->orderBy('created_at', 'DESC')->select('*')->get();
        $transport_journey_pricesData['data'] = $transport_journey_prices;
        echo json_encode($transport_journey_pricesData);
    }


   
    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.transport_journey_prices.create', compact('control', 
        // 'transport_type'
    ));
    }

    public function save(Request $request)
    {
        $transport_journey_prices = new TransportJourneyPrices();
        $this->add_or_update($request, $transport_journey_prices);

        return redirect('admin/transport_journey_prices');
    }
    public function edit($id)
    {
        $control = 'edit';
        $transport_journey_prices = TransportJourneyPrices::find($id);
        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.transport_journey_prices.create', compact(
            'control',
            'transport_journey_prices',
            // 'transport_type',

        )
        );
    }

    public function update(Request $request, $id)
    {
        $transport_journey_prices = TransportJourneyPrices::find($id);
        // TransportJourneyPrices::delete()
        $this->add_or_update($request, $transport_journey_prices);
        return Redirect('admin/transport_journey_prices');
    }


    public function add_or_update(Request $request, $transport_journey_prices)
    {
        // dd($request->all());
        $transport_journey_prices->transport_type_id = $request->transport_type_id;
        $transport_journey_prices->user_owner_id = $request->user_owner_id;
        $transport_journey_prices->details = $request->details;


        $transport_journey_prices->save();


        return redirect()->back();
    }

    public function destroy_undestroy($id)
    {
        $transport_journey_prices = TransportJourneyPrices::find($id);
        if ($transport_journey_prices) {
            TransportJourneyPrices::destroy($id);
            $new_value = 'Activate';
        } else {
            TransportJourneyPrices::withTrashed()->find($id)->restore();
            $new_value = 'Delete';
        }
        $response = Response::json([
            "status" => true,
            'action' => Config::get('constants.ajax_action.delete'),
            'new_value' => $new_value
        ]);
        return $response;
    }
    public function update_price(Request $request, $journey_slot_id)
    {
        $transport_jou_prices = TransportJourneyPrices::find($journey_slot_id);
        $transport_jou_prices->trip_price = $request->trip_price;
        $transport_jou_prices->save();
        return $this->sendResponse(200, $transport_jou_prices);
    }
    public function update_sale_agent(Request $request, $journey_slot_id)
    {
        $transport_sale_agent = TransportJourneyPrices::find($journey_slot_id);
        $transport_sale_agent->sale_agent_user_id = $request->sale_agent_user_id;
        $transport_sale_agent->save();
        return $this->sendResponse(200, $transport_sale_agent);
    }
    public function update_sale_agent_com(Request $request, $journey_slot_id)
    {
        $transport_update_sale_agent_com = TransportJourneyPrices::find($journey_slot_id);
        $transport_update_sale_agent_com->sale_agent_commision = $request->sale_agent_commision;
        $transport_update_sale_agent_com->save();
        return $this->sendResponse(200, $transport_update_sale_agent_com);
    }
    public function update_travel_agent(Request $request, $journey_slot_id)
    {
        $transport_update_travel_agent = TransportJourneyPrices::find($journey_slot_id);
        $transport_update_travel_agent->travel_agent_user_id = $request->travel_agent_user_id;
        $transport_update_travel_agent->save();
        return $this->sendResponse(200, $transport_update_travel_agent);
    }
    public function update_tavel_agent_com(Request $request, $journey_slot_id)
    {
        $transport_update_tavel_agent_com = TransportJourneyPrices::find($journey_slot_id);
        $transport_update_tavel_agent_com->travel_agent_commision = $request->travel_agent_commision;
        $transport_update_tavel_agent_com->save();
        return $this->sendResponse(200, $transport_update_tavel_agent_com);
    }
    public function update_driver_com(Request $request, $journey_slot_id)
    {
        $transport_update_driver_com = TransportJourneyPrices::find($journey_slot_id);
        $transport_update_driver_com->driver_user_commision = $request->driver_user_commision;
        $transport_update_driver_com->save();
        return $this->sendResponse(200, $transport_update_driver_com);
    }

}
