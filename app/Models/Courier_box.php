<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_box extends Model
{
    protected $table = 'courier_boxes';


    public function courier_box_items(){
        return $this->hasMany('App\Models\Courier_box_item','courier_box_id');
    }
}
