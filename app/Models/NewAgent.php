<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewAgent extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'become_travel_agent';
}
