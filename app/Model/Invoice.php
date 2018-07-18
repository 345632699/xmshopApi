<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "invoice_record";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'order_number',
        'order_type',
        'invoice_type',
        'detail',
        'amount',
        'email',
        'title',
        'tax_code',
        'phone_num',
        'invoice_date',
        'client_id',
        'order_id',
    ];
}
