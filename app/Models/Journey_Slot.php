<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journey_Slot extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'journey_slot';   


    
    public function journey()
    {
        return $this->hasOne('App\Models\Journey', 'id', 'journey_id')->withTrashed();
    }
    public function slot()
    {
        return $this->hasOne('App\Models\Slot', 'id', 'slot_id')->withTrashed();
    }
    }
