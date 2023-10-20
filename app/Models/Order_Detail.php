<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order_Detail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order_detail';   
    
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
    public function journey()
    {
        return $this->hasOne('App\Models\Journey', 'id', 'journey_id')->withTrashed();
    }
    public function journey_slot()
    {
        return $this->hasOne('App\Models\Journey_Slot', 'id', 'journey_slot_id')->withTrashed();
    }
    // public function slot()
    // {
    //     return $this->hasOne('App\Models\Slot', 'id', 'slot_id')->withTrashed();
    // }
}
