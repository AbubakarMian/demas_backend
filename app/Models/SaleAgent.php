<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleAgent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sale_agent';

    // protected function CommisionType(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (string $value) => ucwords(str_replace($value),'_',' '),
    //     );
    // }
    public function getCommisionTypeAttribute($value)
    {
        return ucwords(str_replace('_',' ',$value));
    }


    public function user_name()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
    }

    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
    }

    public function travel_agents()
    {
        return $this->hasMany('App\Models\Travel_Agent', 'user_sale_agent_id', 'user_id')->withTrashed();
    }
}