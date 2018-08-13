<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $fillable = ['uid','name','description','thumbnail','update_time'];

    protected $primaryKey = 'uid';


}
