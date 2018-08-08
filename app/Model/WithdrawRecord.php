<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WithdrawRecord extends Model
{
    protected $table = "withdraw_record";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'partner_trade_no',
        'client_id',
        'amount',
        'status',
        'updated_at',
        'created_at',
        'success_time'
    ];
}
