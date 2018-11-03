<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Payment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function agent(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
