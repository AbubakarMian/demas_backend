<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transport extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transport';

    protected $casts = [
        'images' => 'array',
        // 'properties' => 'object'
    ];

    public function transport_type(){
        return $this->hasOne('App\Models\Transport_Type','id','transport_type_id');
    }
    public function transport_price(){
        return $this->hasMany('App\Models\TransportPrices','transport_type_id','transport_type_id');
    }
    // public function transport_sale_agent_price(){
    //     return $this->hasMany('App\Models\TransportPrices','transport_type_id','transport_type_id');
    // }
    // public function transport_travel_agent_price(){
    //     return $this->hasMany('App\Models\TransportPrices','transport_type_id','transport_type_id');
    // }
    
}
