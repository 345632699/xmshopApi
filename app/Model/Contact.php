<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $table = "client_delivery_contact";

    protected $fillable = [
        'uid',
        'name',
        'client_id',
        'phone_num',
        'province',
        'city',
        'area',
        'address',
        'update_time',
        'default_flag'
    ];

    protected $primaryKey = 'uid';
}
