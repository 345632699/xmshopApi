<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order_headers";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'order_number',
        'order_type',
        'order_status',
        'client_id',
        'order_date',
        'pay_date',
        'completion_date',
        'return_date',
        'contract_id',
        'request_close_date',
        'open_invoice_flag',
        'cancel_reason',
        'updated_at',
        'created_at',
    ];

}
