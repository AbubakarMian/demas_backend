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
        return $this->hasOne('App\Models\SaleAgent', 'user_id', 'sale_agent_user_id')->withTrashed();
    }
    public function travel_agent()
    {
        return $this->hasOne('App\Models\Travel_Agent', 'user_id', 'travel_agent_user_id')->withTrashed();
    }
    public function driver()
    {
        return $this->hasOne('App\Models\Driver', 'user_id', 'user_driver_id')->withTrashed();
    }
    public function order_details()
    {
        return $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')->withTrashed();
    }


}
