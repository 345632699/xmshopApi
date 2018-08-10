<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_lines";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'header_id',
        'good_id',
        'color',
        'combo_id',
        'robot_id',
        'quantity',
        'unit_price',
        'unit_price',
        'buyer_msg',
        'total_price',
        'updated_at',
        'created_at'
    ];

}
