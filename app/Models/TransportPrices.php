<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportPrices extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transport_prices';

    function journey(){
        return $this->hasOne('App\Models\Journey','id','journey_id');
    }
    function slot(){
        return $this->hasOne('App\Models\Slot','id','slot_id');
    }
    function transport_type(){
        return $this->hasOne('App\Models\Transport_Type','id','transport_type_id');
    }
}
