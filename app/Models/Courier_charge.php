<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_charge extends Model
{
    //

    public function courier_service(){
        return $this->belongsTo('App\Models\Courier_service','courier_service_id','id');
    }
}
