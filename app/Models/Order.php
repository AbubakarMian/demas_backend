<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order';   
    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_driver_id')->withTrashed();
    }
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id')->withTrashed();
    }
    public function sale_agent()
    {
        return $this->hasOne('App\Models\SaleAgent', 'user_id', 'user_sale_agent_id')->withTrashed();
    }
    public function travel_agent()
    {
        return $this->hasOne('App\Models\Travel_Agent', 'user_id', 'user_travel_agent_id')->withTrashed();
    }


}
