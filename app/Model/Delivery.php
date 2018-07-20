<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = "delivery";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'delivery_number',
        'order_header_id',
        'delivery_contact_id',
        'creation_date',
        'delivery_date',
        'delivery_status',
        'address',
        'updated_at',
        'created_at'
    ];
}
