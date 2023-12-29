<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffPaymentsIncoming extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'staff_payment_incomming';
    public function user_obj()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id')->withTrashed();
    }
}
