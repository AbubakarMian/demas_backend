<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'users';
    
    public function role(){
        return $this->hasOne('App\Models\Role','id','role_id');
    }

    public function sale_agent(){
        return $this->hasOne('App\Models\SaleAgent','user_id','id');
    }
    public function travel_agent(){
        return $this->hasOne('App\Models\Travel_Agent','user_id','id');
    }
    public function driver(){
        return $this->hasOne('App\Models\Driver','user_id','id');
    }
    
}
