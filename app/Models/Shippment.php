<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Shippment extends Model
{
    //use SoftDeletes;
    //protected $dates = ['deleted_at'];

    protected $fillable = [
        'courier_id', 'package_type_id', 'service_type_id','content_type_id',
        'weight', 'carriage_value'
    ];
}
