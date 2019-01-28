<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payment extends Model
{
//    use SoftDeletes;
//    protected $dates = ['deleted_at'];

    public function agent(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function courier(){
        return $this->belongsTo('App\Models\Courier','customer_phone','s_phone');
    }
}
