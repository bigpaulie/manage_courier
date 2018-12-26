<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier_box_item extends Model
{
    protected $table = 'courier_box_items';

    public function content_type(){
        return $this->belongsTo('App\Models\Content_type','content_type_id','id');
    }
}
