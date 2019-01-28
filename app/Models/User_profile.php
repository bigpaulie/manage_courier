<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User_profile extends Model
{
//    use SoftDeletes;
//
//    protected $dates = ['deleted_at'];

    public function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }

    public function store(){
        return $this->belongsTo('App\Models\User','store_id','id');
    }
}
