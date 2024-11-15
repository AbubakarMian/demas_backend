<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journey_Slot;
use App\Models\SaleAgent;
use App\Models\Transport_Type;
use App\Models\TransportJourneyPrices;
use App\Models\Travel_Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class TransportJourneyPricesController extends Controller
{

    // public function index(Request $request)
    // {
        // return view('admin.transport_journey_prices.index');
    // }

    // public function get_transport_journey_prices(Request $request)
    // {
        // $journey_slots = Journey_Slot::get();
        // // $travel_agents = Travel_Agent::with('sale_agent')->get();
        // $transport_types = Transport_Type::get();


        // foreach ($journey_slots as $slot_key => $j_slot) {
        //     foreach ($transport_types as $transport_type_key => $transport_type) {
        //         # code...
        //         // foreach ($s_agent->travel_agents as $travel_agent_key => $t_agent) {

        //             // $sale_agent_id = isset($t_agent->sale_agent)?$t_agent->sale_agent->user_id:0;
        //             // $travel_agent_user_id = $t_agent->user_id;
        //             $journey_slot_id = $j_slot->id;

        //             $transport_prices_obj = TransportJourneyPrices::
        //                 where('journey_slot_id', $journey_slot_id)
        //                 ->first(); // Use 'first' to retrieve a single recordss

        //             if (!$transport_prices_obj) {
        //                 $transport_prices_obj = new TransportJourneyPrices();
        //                 $transport_prices_obj->sale_agent_user_id = $sale_agent_id;
        //                 $transport_prices_obj->travel_agent_user_id = $travel_agent_user_id;
        //                 $transport_prices_obj->journey_slot_id = $journey_slot_id;
        //                 // $transport_prices_obj->price = 0;
        //                 $transport_prices_obj->save();
        //             }
        //     }
        // }

        // $transport_prices_arr = TransportJourneyPrices::with([
        //     'journeyslot'=>['slot','journey'],'travel_agent'=>['user_obj','sale_agent.user_obj']
        // ])
        //     ->get();
    //     return $this->sendResponse(200, $transport_prices_arr);
    // }

    // public function get_transport_journey_prices_del(Request $request)
    // {
        // $transport_journey_prices = TransportJourneyPrices::with([
        //     'journeyslot.slot',
        // ])->orderBy('created_at', 'DESC')->get();
        // $data = [];
        // $journey_slots = Journey_Slot::get()->toArray();
        // $travel_agents = Travel_Agent::with('sale_agent')->get();


        // foreach ($journey_slots as $slot_key => $j_slot) {
        //     foreach ($travel_agents as $journey_key => $t_agent) {
        //         # code...
        //         // foreach ($s_agent->travel_agents as $travel_agent_key => $t_agent) {

        //             $sale_agent_id = isset($t_agent->sale_agent)?$t_agent->sale_agent->user_id:0;
        //             $travel_agent_user_id = $t_agent->user_id;
        //             $journey_slot_id = $j_slot['id'];

        //             $transport_prices_obj = TransportJourneyPrices::
        //                 where('journey_slot_id', $journey_slot_id)
        //                 ->where('sale_agent_user_id', $sale_agent_id)
        //                 ->where('travel_agent_user_id', $travel_agent_user_id)
        //                 ->first(); // Use 'first' to retrieve a single recordss

        //             if (!$transport_prices_obj) {
        //                 $transport_prices_obj = new TransportJourneyPrices();
        //                 $transport_prices_obj->sale_agent_user_id = $sale_agent_id;
        //                 $transport_prices_obj->travel_agent_user_id = $travel_agent_user_id;
        //                 $transport_prices_obj->journey_slot_id = $journey_slot_id;
        //                 // $transport_prices_obj->price = 0;
        //                 $transport_prices_obj->save();
        //             }
        //     }
        // }

        // $transport_prices_arr = TransportJourneyPrices::with([
        //     'journeyslot'=>['slot','journey'],'travel_agent'=>['user_obj','sale_agent.user_obj']
        // ])
        //     ->get();
    //     return $this->sendResponse(200, $transport_prices_arr);
    // }



    // public function create()
    // {
    //     $control = 'create';
    //     return view('admin.transport_journey_prices.create', compact(
    //         'control',
    //     )
    //     );
    // }

    // public function save(Request $request)
    // {
    //     $transport_journey_prices = new TransportJourneyPrices();
    //     $this->add_or_update($request, $transport_journey_prices);

    //     return redirect('admin/transport_journey_prices');
    // }
    // public function edit($id)
    // {
    //     $control = 'edit';
    //     $transport_journey_prices = TransportJourneyPrices::find($id);
    //     // $courses = Courses::pluck('full_name','id');
    //     // $travel_agent = travel_agent::pluck('name', 'id');
    //     return view(
    //         'admin.transport_journey_prices.create',
    //         compact(
    //             'control',
    //             'transport_journey_prices',
    //             // 'travel_agent',

    //         )
    //     );
    // }

    // public function update(Request $request, $id)
    // {
    //     $transport_journey_prices = TransportJourneyPrices::find($id);
    //     // TransportJourneyPrices::delete()
    //     $this->add_or_update($request, $transport_journey_prices);
    //     return Redirect('admin/transport_journey_prices');
    // }


    // public function add_or_update(Request $request, $transport_journey_prices)
    // {
    //     // dd($request->all());
    //     $transport_journey_prices->travel_agent_id = $request->travel_agent_id;
    //     $transport_journey_prices->user_owner_id = $request->user_owner_id;
    //     $transport_journey_prices->details = $request->details;


    //     $transport_journey_prices->save();


    //     return redirect()->back();
    // }

    // public function destroy_undestroy($id)
    // {
    //     $transport_journey_prices = TransportJourneyPrices::find($id);
    //     if ($transport_journey_prices) {
    //         TransportJourneyPrices::destroy($id);
    //         $new_value = 'Activate';
    //     } else {
    //         TransportJourneyPrices::withTrashed()->find($id)->restore();
    //         $new_value = 'Delete';
    //     }
    //     $response = Response::json([
    //         "status" => true,
    //         'action' => Config::get('constants.ajax_action.delete'),
    //         'new_value' => $new_value
    //     ]);
    //     return $response;
    // }
    // public function update_price(Request $request, $journey_slot_id)
    // {
    //     $transport_jou_prices = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_jou_prices->trip_price = $request->trip_price;
    //     $transport_jou_prices->save();
    //     return $this->sendResponse(200, $transport_jou_prices);
    // }
    // public function update_sale_agent(Request $request, $journey_slot_id)
    // {
    //     $transport_sale_agent = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_sale_agent->sale_agent_user_id = $request->sale_agent_user_id;
    //     $transport_sale_agent->save();
    //     return $this->sendResponse(200, $transport_sale_agent);
    // }
    // public function update_sale_agent_com(Request $request, $journey_slot_id)
    // {
    //     $transport_update_sale_agent_com = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_update_sale_agent_com->sale_agent_commision = $request->sale_agent_commision;
    //     $transport_update_sale_agent_com->save();
    //     return $this->sendResponse(200, $transport_update_sale_agent_com);
    // }
    // public function update_travel_agent(Request $request, $journey_slot_id)
    // {
    //     $transport_update_travel_agent = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_update_travel_agent->travel_agent_user_id = $request->travel_agent_user_id;
    //     $transport_update_travel_agent->save();
    //     return $this->sendResponse(200, $transport_update_travel_agent);
    // }
    // public function update_tavel_agent_com(Request $request, $journey_slot_id)
    // {
    //     $transport_update_tavel_agent_com = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_update_tavel_agent_com->travel_agent_commision = $request->travel_agent_commision;
    //     $transport_update_tavel_agent_com->save();
    //     return $this->sendResponse(200, $transport_update_tavel_agent_com);
    // }
    // public function update_driver_com(Request $request, $journey_slot_id)
    // {
    //     $transport_update_driver_com = TransportJourneyPrices::find($journey_slot_id);
    //     $transport_update_driver_com->driver_user_commision = $request->driver_user_commision;
    //     $transport_update_driver_com->save();
    //     return $this->sendResponse(200, $transport_update_driver_com);
    // }
    // public function update_agent_com(Request $request, $transport_journey_price_id)
    // {
    //     $transport_update_driver_com = TransportJourneyPrices::find($transport_journey_price_id);
    //     $type = $request->type;
    //     if($type == 'sale'){
    //         $transport_update_driver_com->sale_agent_commision = $request->commision;
    //     }
    //     else if($type == 'travel'){
    //     $transport_update_driver_com->travel_agent_commision = $request->commision;
    //     }
    //     else{// driver
    //         $transport_update_driver_com->driver_user_commision = $request->commision;
    //     }
    //     $transport_update_driver_com->save();
    //     return $this->sendResponse(200, $transport_update_driver_com);
    // }

}