<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportJourneyPrices extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transport_journey_prices'; 

    public function journeyslot()
    {
        return $this->hasOne('App\Models\Journey_Slot', 'id', 'journey_slot_id')->withTrashed();
    }
    
    public function sale_agent()
    {
        return $this->hasOne('App\Models\SaleAgent', 'user_id', 'sale_agent_user_id')->withTrashed();
    }
    
    public function travel_agent()
    {
        return $this->hasOne('App\Models\Travel_Agent', 'user_id', 'travel_agent_user_id')->withTrashed();
    }
  }
