<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Transport_Type extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transport_type'; 

    function transports(){
        return $this->hasMany('App\Models\Transport','transport_type_id','id');
    }
}
