<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = ['uid','name','description','unit_price','original_unit_price','update_time'];

    protected $primaryKey = 'uid';


}
