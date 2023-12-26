<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Order_Detail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order_detail';
    protected $appends = ['ispayable'];


    public function order()
    {
        return $this->hasOne('App\Models\Order', 'id', 'order_id')->withTrashed();
    }
    public function pickup_location()
    {
        return $this->hasOne('App\Models\Locations', 'id', 'pickup_location_id')->withTrashed();
    }
    public function dropoff_location()
    {
        return $this->hasOne('App\Models\Locations', 'id', 'drop_off_location_id')->withTrashed();
    }
    public function driver()
    {
        return $this->hasOne('App\Models\Driver', 'user_id', 'driver_user_id')->withTrashed();
    }
    public function sale_agent()
    {
        return $this->hasOne('App\Models\SaleAgent', 'user_id', 'sale_agent_user_id')->withTrashed();
    }
    public function travel_agent()
    {
        return $this->hasOne('App\Models\Travel_Agent', 'user_id', 'travel_agent_user_id')->withTrashed();
    }
    public function driver_user()
    {
        return $this->hasOne('App\Models\Users', 'id', 'driver_user_id')->withTrashed();
    }
    public function sale_agent_user()
    {
        return $this->hasOne('App\Models\Users', 'id', 'sale_agent_user_id')->withTrashed();
    }
    public function travel_agent_user()
    {
        return $this->hasOne('App\Models\Users', 'id', 'travel_agent_user_id')->withTrashed();
    }
    public function transport()
    {
        return $this->hasOne('App\Models\Transport', 'id', 'transport_id')->withTrashed();
    }
    public function transport_type()
    {
        return $this->hasOne('App\Models\Transport_Type', 'id', 'transport_type_id')->withTrashed();
    }
    public function journey()
    {
        return $this->hasOne('App\Models\Journey', 'id', 'journey_id')->withTrashed();
    }
    public function journey_slot()
    {
        return $this->hasOne('App\Models\Journey_Slot', 'id', 'journey_slot_id')->withTrashed();
    }
    public function getIsPayableAttribute() // is_payable
    {
        if ($this->attributes['user_payment_status'] == Config::get('constants.user_payment_status.pending')) {
            return true;
        }
        return false;
    }
}
