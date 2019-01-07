<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_payment extends Model
{
    protected $fillable = ['courier_id','user_id','pay_amount','remaining','discount','total','payment_date'];
}
