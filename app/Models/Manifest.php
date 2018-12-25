<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    protected $table = 'manifest';


    public function vendor(){
        return $this->belongsTo('App\Models\Vendor','vendor_id','id');
    }

    public function manifest_items(){
        return $this->hasMany('App\Models\Manifest_item','manifest_id');
    }
}
