<?php

namespace App\Http\Controllers\Handler;

use App\Libraries\APIResponse;
use App\Libraries\Common;
use App\Models\Journey;
use App\Models\Settings;
use App\Models\Slot;
use App\Models\Transport;
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

        $journey_res = json_decode($this->get_journey($request));
        if (!$journey_res) {
            return $journey_res;
        }

        $journey = $journey_res->response;

        $slot = $this->get_slot($request);

        $cars = $this->get_cars($request, $journey, $slot, $car_id);

        $user = $request->attributes->get('user');
        $travel_agent = Travel_Agent::where('user_id', $user->id)->first();

        $cars = $this->transform_cars($journey, $slot, $cars, $travel_agent);
        return $cars;
    }
    public function get_journey(Request $request)
    {
        // $request = $this->request;
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

        $cars = Transport::with([
            'transport_type',
            'transport_price' => function ($query) use ($journey, $slot) {
                if ($journey) {
                    $query = $query->where('journey_id', $journey->id);
                }
                $query->where('slot_id', $slot->id)
                    ->latest();
            }
        ]);
        if ($car_id) {
            $cars = $cars->where('id', $car_id);
        } else if ($request->transport_type_id) {
            $cars = $cars->where('transport_type_id', $request->transport_type_id); //->paginate(500);
        }
        $cars = $cars->orderByDesc('created_at')->paginate(3);
        return $cars;
    }

    public function transform_cars($journey, $slot, $cars, $travel_agent)
    {

        $discount_percent_obj = Settings::where('name', Config::get('constants.settings.discount'))
                                        ->first();
        $discount_percent = $discount_percent_obj->value;
        $cars->transform(function ($item) use ($travel_agent, $journey, $slot, $discount_percent) {
            $commission = 0;
            if ($travel_agent) {
                $travel_agent_commission = TravelAgentCommission::where('user_travel_agent_id', $travel_agent->user_id)
                    ->where('journey_id', $journey->id)
                    ->where('slot_id', $slot->id)
                    ->where('transport_type_id', $item->transport_type_id)
                    ->first();
                $commission = $travel_agent_commission->commission;
            }
            // $transport_price = TransportPrices::
            //   where('journey_id',$journey->id)
            // ->where('slot_id',$slot->id)
            // ->where('transport_type_id',$item->transport_type_id)
            // ->first();
            $item->features = isset($item->features) ? explode(',', $item->features) : [];
            $item->booking =  isset($item->booking) ? explode(',', $item->booking) : [];
            $item->dontforget = isset($item->dontforget) ? explode(',', $item->dontforget) : [];
            $item->actual_price = '0';
            $item->booking_price = '0';
            $item->discounted_price = '0';
            $transport_prices = $item->transport_price;
            if (isset($transport_prices) && count($transport_prices)) {
                $transport_price = $transport_prices[0];
                $item->transport_price_id = $transport_price->id;
                $item->actual_price = $transport_price->price;
                $item->booking_price = $transport_price->price - $commission;
                $item->discounted_price = $transport_price->price - ($transport_price->price * $discount_percent * 0.01);
            }
            $res[] = $item;
            return $item;
        });
        return $cars;
    }

    public function get_all(Request $request)
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

        $cars->transform(function ($item) use ($travel_agent, $journey, $slot) {
            $commission = 0;
            if ($travel_agent) {
                $travel_agent_commission = TravelAgentCommission::where('user_travel_agent_id', $travel_agent->user_id)
                    ->where('journey_id', $journey->id)
                    ->where('slot_id', $slot->id)
                    ->where('transport_type_id', $item->transport_type_id)
                    ->first();
                $commission = $travel_agent_commission->commission;
            }
            $transport_price = TransportPrices::where('journey_id', $journey->id)
                ->where('slot_id', $slot->id)
                ->where('transport_type_id', $item->transport_type_id)
                ->first();
            $item->features = isset($item->features) ? explode(',', $item->features) : [];
            $item->booking =  isset($item->booking) ? explode(',', $item->booking) : [];
            $item->dontforget = isset($item->dontforget) ? explode(',', $item->dontforget) : [];
            $item->actual_price = '0';
            $item->booking_price = '0';
            if ($transport_price) {
                $item->transport_price_id = $transport_price->id;
                $item->actual_price = $transport_price->price;
                $item->booking_price = $transport_price->price - $commission;
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
}
