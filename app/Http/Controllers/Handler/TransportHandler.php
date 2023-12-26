<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Settings;
use App\Models\Slot;
use App\Models\Transport;
use App\Models\Transport_Type;
use App\Models\TransportPrices;
use App\Models\Travel_Agent;
use App\Models\TravelAgentCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TransportHandler
{
    use Common, APIResponse;

    public function __constructor()
    {
    }

    public function get_car_details(Request $request, $car_id = 0)
    {

        $user = $request->attributes->get('user');
        $journey_res = json_decode($this->get_journey($request));
        if (!$journey_res) {
            return $journey_res;
        }

        $journey = $journey_res->response;
        $slot = $this->get_slot($request);
        $cars = $this->get_cars($request, $journey, $slot, $car_id);

        $travel_agent = Travel_Agent::where('user_id', $user->id)->first();
        $request_params = $request->all();
        $booking = $request_params['booking_details'];
        $apply_discount = (count($booking['details']) + 1) % 3 == 0 && $user->role_id == 2;
        $cars = $this->transform_cars($user,$cars, $apply_discount);
        return $cars;
    }
    public function get_all_cars(Request $request)
    {
        $cars = Transport::with('transport_type');
        $cars = $this->apply_filters_car_list($request, $cars);
        $cars = $cars->orderByDesc('created_at')->paginate(1000);
        return $cars;
    }
    public function get_journey(Request $request)
    {
        $pickup_id = $request->pickup_id ?? 0;
        $dropoff_id = $request->dropoff_id ?? 0;

        $journey = Journey::where('pickup_location_id', $pickup_id)
            ->where('dropoff_location_id', $dropoff_id)->first();
        if (!$journey) {
            return $this->sendResponse(
                500,
                null,
                ['This journey is currently unavalible for booking']
            );
        }
        return $this->sendResponse(200, $journey);
    }

    public function get_slot(Request $request)
    {

        $pickupdate_time = $request->pickupdate_time ?? 0;

        $slot = Slot::where('start_date', '<=', $pickupdate_time)
            ->where('end_date', '>=', $pickupdate_time)
            ->orderbyDesc('created_at')
            ->first();

        if (!$slot) {
            $slot = Slot::where('start_date', '0')
                ->where('end_date', '0')
                ->first();
        }
        return $slot;
    }

    public function get_cars(Request $request, $journey, $slot, $car_id = 0)
    {
        $user = $request->attributes->get('user');
// dd($journey->id,$slot->id);
        $with = [
            'transport_type',
            'transport_price' => function ($q_transport_price) use ($journey, $slot,$user) {
                if ($journey) {
                    $q_transport_price = $q_transport_price->where('journey_id', $journey->id);
                }
                $q_transport_price->where('slot_id', $slot->id);
            },
            'transport_price.sale_agent_trip_price'=>function($sale_agent_trip_price) use ($journey, $slot,$user){
                $sale_agent_trip_price->where('user_sale_agent_id', $user->id);
            },
            'transport_price.travel_agent_trip_price'=>function($travel_agent_trip_price) use ($journey, $slot,$user){
               $travel_agent_trip_price->where('user_travel_agent_id', $user->id);
            },
        ];

        $cars = Transport::with($with);
        if ($car_id) {
            $cars = $cars->where('id', $car_id);
        }
        // if ($journey) {
        //     $cars = $cars->whereHas('transport_price', function ($q_transport_price) use ($journey, $slot,$user) {
        //         // if ($journey) {
        //         //     $q_transport_price = $q_transport_price->where('journey_id', $journey->id);
        //         // }
        //         // $q_transport_price->where('slot_id', $slot->id)->first();
        //     });
        // }
        $cars = $this->apply_filters_car_list($request, $cars);
        $cars = $cars->orderByDesc('created_at')->paginate(1000);
        // dd($cars[0]);
        return $cars;
    }

    public function apply_filters_car_list(Request $request, $cars)
    {

        if ($request->transport_type_id) {
            $cars = $cars->where('transport_type_id', $request->transport_type_id);
        }
        if ($request->seats) {
            $cars = $cars->where('seats', $request->seats);
        }
        if ($request->luggage) {
            $cars = $cars->where('luggage', $request->luggage);
        }
        return $cars;
    }

    public function transform_cars($user, $cars, $apply_discount = false)
    {
        $discount_percent_obj = Settings::where('name', Config::get('constants.settings.discount'))
            ->first();
        $discount_percent = $discount_percent_obj->value;

        $cars = $cars->transform(function ($car_item) use ($user, $discount_percent, $apply_discount) {

            $item = new \stdClass();
            $item->id = $car_item->id;
            $item->transport_type_id = $car_item->transport_type_id;
            $item->details = $car_item->details;
            $item->name = $car_item->name;
            $item->seats = $car_item->seats;
            $item->luggage = $car_item->luggage;
            $item->doors = $car_item->doors;
            $item->number_plate = $car_item->number_plate;
            $item->owner_name = $car_item->owner_name;
            $item->features = isset($car_item->features) ? explode(',', $car_item->features) : [];
            $item->booking =  isset($car_item->booking) ? explode(',', $car_item->booking) : [];
            $item->dontforget = isset($car_item->dontforget) ? explode(',', $car_item->dontforget) : [];
            $item->actual_price = '0';
            $item->booking_price = '0';
            $item->discounted_price = '0';
            $item->apply_discount = $apply_discount;
            $transport_price = null;
            
            if(isset($car_item->transport_price[0])){
                $transport_price = $car_item->transport_price[0];
            }
            // if(isset($transport_price->sale_agent_trip_price)){
            if(isset($user->sale_agent)){
                $transport_price = $transport_price->sale_agent_trip_price;
            }
            // else if(isset($item->transport_price->travel_agent_trip_price)){
            else if(isset($user->travel_agent)){
                $transport_price = $transport_price->travel_agent_trip_price;
            }
            else{ //user
                // $transport_price = $car_item->transport_price[0];
                // dd($transport_price);
                if(isset($car_item->transport_price[0])){
                    $transport_price = $car_item->transport_price[0];
                }
                $item->transport_price_id = $transport_price->id;
            }
            if(isset($transport_price)){
                $item->actual_price = $transport_price->price;
                $item->booking_price = $transport_price->price; 
                $item->discounted_price = $transport_price->price - ($transport_price->price * $discount_percent * 0.01);
            }
            $item->transport_price = $transport_price;
            $item->transport_type = $car_item->transport_type;
            // if(!isset($item->transport_type)){
            //     $item->transport_type = $transport_price->transport_type;

            // }
            $item->images = $car_item->images;
            return $item;
        });
        return $cars;
    }

    public function get_all(Request $request)
    {

        $pickup_id = $request->pickup_id ?? 0;
        $dropoff_id = $request->dropoff_id ?? 0;

        $discount_percent_obj = Settings::where('name', Config::get('constants.settings.discount'))
            ->first();
        $discount_percent = $discount_percent_obj->value;

        $journey = Journey::where('pickup_location_id', $pickup_id)
            ->where('dropoff_location_id', $dropoff_id)->first();
        if (!$journey) {
            return $this->sendResponse(
                500,
                null,
                ['This journey is currently unavalible for booking']
            );
        }

        $pickupdate_time = $request->pickupdate_time ?? 0;
        $slot = Slot::where('start_date', '<=', $pickupdate_time)
            ->where('end_date', '>=', $pickupdate_time)
            ->orderbyDesc('created_at')
            ->first();

        if (!$slot) {
            $slot = Slot::where('start_date', '0')
                ->where('end_date', '0')
                ->first();
        }
        $cars = Transport::with('transport_type', 'transport_price');
        if ($request->transport_type_id) {
            $cars = $cars->where('transport_type_id', $request->transport_type_id); //->paginate(500);
        }
        if ($request->journey_id) {
            $cars->whereHas('transport_price', function ($query) use ($journey, $slot) {
                $query->where('journey_id', $journey->id)
                    ->where('slot_id', $slot->id);
            });
        }
        $cars = $cars->orderByDesc('created_at')->paginate(500);

        $user = $request->attributes->get('user');

        $travel_agent = Travel_Agent::where('user_id', $user->id)->first();

        $cars->transform(function ($item) use ($travel_agent, $journey, $slot, $discount_percent) {
            $commission = 0;
            if ($travel_agent) {
                $transport_price = TravelAgentCommission::where('user_travel_agent_id', $travel_agent->user_id)
                    ->where('journey_id', $journey->id)
                    ->where('slot_id', $slot->id)
                    ->where('transport_type_id', $item->transport_type_id)
                    ->first();
                // $price = $travel_agent_commission->price;
            } else {
                $transport_price = TransportPrices::where('journey_id', $journey->id)
                    ->where('slot_id', $slot->id)
                    ->where('transport_type_id', $item->transport_type_id)
                    ->first();
            }

            $item->features = isset($item->features) ? explode(',', $item->features) : [];
            $item->booking =  isset($item->booking) ? explode(',', $item->booking) : [];
            $item->dontforget = isset($item->dontforget) ? explode(',', $item->dontforget) : [];
            $item->actual_price = '0';
            $item->booking_price = '0';
            if ($transport_price) {
                $item->transport_price_id = $transport_price->id;
                $item->actual_price = $transport_price->price;
                $item->booking_price = $transport_price->price;
                $item->discounted_price = $transport_price->price - ($transport_price->price * $discount_percent * 0.01);
            }
            return $item;
        });
        $cars = $cars->items();
        return $this->sendResponse(200, $cars);
    }

    public function car_details($car_id)
    {
        $car = Transport::with('transport_type')->find($car_id);
        return $this->sendResponse(200, $car);
    }

    public function get_cars_by_types(Request $request)
    {
        $type_id = $request->type_id ?? 0;
        $transport_types = Transport_Type::with('transports');

        if ($type_id) {
            $transport_types = $transport_types->where('id', $type_id);
        }
        $transport_types = $transport_types->get();
        return $transport_types;
    }

    public function get_transport_types(Request $request)
    {
        // $type = Transport_Type::groupBy(['name','id'])->orderBy('name','asc')->get(['id','name']);
        // $seats = Transport_Type::groupBy('seats')->orderBy('seats','asc')->get(['seats']);
        // $luggage = Transport_Type::groupBy('luggage')->orderBy('luggage','asc')->get(['luggage']);


        $type = Transport_Type::distinct('name')->orderBy('name', 'asc')->get(['id', 'name']);
        $seats = Transport::distinct('seats')->orderBy('seats', 'asc')->get(['seats']);
        $luggage = Transport::distinct('luggage')->orderBy('luggage', 'asc')->get(['luggage']);

        return [
            'type' => $type,
            'seats' => $seats,
            'luggage' => $luggage,
        ];
    }
}
