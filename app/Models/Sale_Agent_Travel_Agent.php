<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;
class Sale_Agent_Travel_Agent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sale_agent_travel_agent';   
   
}
