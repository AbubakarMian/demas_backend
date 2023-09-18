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
        $transport_journey_prices = TransportJourneyPrices::with('transport_type','driver.user')->orderBy('created_at', 'DESC')->select('*')->get();
        $transport_journey_pricesData['data'] = $transport_journey_prices;
        echo json_encode($transport_journey_pricesData);
    }


   
    public function create()
    {
        $control = 'create';
        // $courses = Courses::pluck('full_name','id');
        // $transport_type = Transport_Type::pluck('name', 'id');
        return view('admin.transport_journey_prices.create', compact('control', 'transport_type'));
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
    }}
