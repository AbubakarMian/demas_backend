<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Travel_Agent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'travel_agent';

    public function user_name()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
    }
    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
    }
    public function sale_agent(){
        return $this->hasOne('App\Models\SaleAgent','user_id','user_sale_agent_id');
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}