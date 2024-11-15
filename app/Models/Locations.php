<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Locations extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'locations';   

    public function location_type()
    {
        return $this->hasOne('App\Models\Location_Type', 'id', 'location_type_id')->withTrashed();
    }


    }
