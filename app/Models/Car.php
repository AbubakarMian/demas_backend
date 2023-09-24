<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transport';

    protected $casts  = [
        'images' => 'array',
    ];


    public function transport_type()
    {
        return $this->hasOne('App\Models\Transport_Type', 'id', 'transport_type_id')->withTrashed();
    }

    public function driver()
    {
        return $this->hasOne('App\Models\Driver', 'id', 'user_driver_id')->withTrashed();
    }
}
