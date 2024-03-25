<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'order';
    protected $appends = ['orderdetailsstatus','ispayable','orderpayable',
    // 'activeorderdetails'
];
    public $in_active_orders_status = [
            'cancelled',
            'rejected',
            ];
            public $active_orders_status = [
                'pending',
                'accepted',
                'confirm',
                ];
    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
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
    public function sale_agent_user()
    {
        return $this->hasOne('App\Models\Users', 'id', 'sale_agent_user_id')->withTrashed();
    }
    public function travel_agent_user()
    {
        return $this->hasOne('App\Models\Users', 'id', 'travel_agent_user_id')->withTrashed();
    }
   
    // public function driver()
    // {
    //     return $this->hasOne('App\Models\Driver', 'user_id', 'driver_user_id')->withTrashed();
    // }
    // public function getActiveOrderDetailsAttribute()
    public function active_order_details()
    {
        return $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')
            ->whereIn('status',$this->active_orders_status);
        // ->get();
    }
    public function order_details()
    {
        return $this->hasMany('App\Models\Order_Detail', 'order_id', 'id');
    }
    public function order_details_status()
    {   
        return $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')
        ->select('status', \DB::raw('count(*) as count'))
        ->groupBy('status');
    }

    public function getOrderDetailsStatusAttribute()
    {
        // $post->custom;
        $order_status = '';
        $comma = '';
        $order_details = $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')->get();
        foreach ($order_details as $key => $order_detail) {
            $order_status .= $comma.ucfirst($order_detail->status);
            $comma = ',';
        }
        // $title = $this->attributes['status']; 

        return $order_status;
    }

    public function getIsPayableAttribute() // is_payable
    {
        return $this->getOrderPayableAttribute() ? true : false;
        // $order_details = $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')->get();
        // foreach ($order_details as $key => $order_detail) {
        //     if($order_detail->user_payment_status == Config::get('constants.user_payment_status.pending')
        //         && !in_array($order_detail->status , [
        //     Config::get('constants.order_status.cancelled'),
        //     Config::get('constants.order_status.rejected'),
        //     ])
        //     ){
        //         return true;
        //     }
        // }
        // return false;
    }

    public function getOrderPayableAttribute() // is_payable
    {
        $payable = 0;
        $order_details = $this->hasMany('App\Models\Order_Detail', 'order_id', 'id')->get();
        foreach ($order_details as $key => $order_detail) {
            if($order_detail->user_payment_status == Config::get('constants.user_payment_status.pending')
                && !in_array($order_detail->status , [
            Config::get('constants.order_status.cancelled'),
            Config::get('constants.order_status.rejected'),
            ])
            ){
                $payable = $payable+$order_detail->customer_collection_price;
            }
        }
        return $payable;
    }

    
}
