<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_payment extends Model
{
    protected $fillable = ['courier_id','user_id','pay_amount','remaining','discount','total','payment_date'];

    public function agent(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function courier(){
        return $this->belongsTo('App\Models\Courier','courier_id','id');
    }
}
