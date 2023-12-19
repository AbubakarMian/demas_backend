<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelAgentCommission extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'travel_agent_commission';
    // protected $appends = ['price'];

    // public function getPriceAttribute()
    // {
    //     return $this->commission;
    // }
    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_travel_agent_id')->withTrashed();
    }
    function journey(){
        return $this->hasOne('App\Models\Journey','id','journey_id');
    }
    function slot(){
        return $this->hasOne('App\Models\Slot','id','slot_id');
    }
    function transport_type(){
        return $this->hasOne('App\Models\Transport_Type','id','transport_type_id');
    }
    function travel_agent(){
        return $this->hasOne('App\Models\Travel_Agent','user_id','user_travel_agent_id');
    }
    function sale_agent_commission(){
        return $this->hasMany('App\Models\SalesAgentTripPrice','transport_price_id','transport_price_id');
    }
}
