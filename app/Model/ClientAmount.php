<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ClientAmount extends Model
{
    protected $table = "client_amount";

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'client_id',
        'amount',
        'freezing_amount',
    ];
}
