<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverJourney extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'driver_journey';  
    public function journey()
    {
        return $this->hasOne('App\Models\Journey', 'id', 'journey_id')->withTrashed();
    }
    public function journey_slot()
    {
        return $this->hasOne('App\Models\Journey_Slot', 'id', 'journey_slot_id')->withTrashed();
    }
    }
