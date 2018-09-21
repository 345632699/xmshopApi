<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientCoupons extends Model
{
    protected $table = "client_coupons";

    protected $fillable = [
        'uid',
        'coupon_id',
        'coupon_amount',
        'expired_date',
        'client_id',
        'spreader_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $primaryKey = 'uid';
}
