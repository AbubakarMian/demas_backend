<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journey extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'journey';

    public function pickup(){
        return $this->hasOne('App\Models\Locations', 'id', 'pickup_location_id')->withTrashed();

    }
    public function dropoff(){
        return $this->hasOne('App\Models\Locations', 'id', 'dropoff_location_id')->withTrashed();

    }
}
