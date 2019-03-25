<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifest_item extends Model
{
    protected $table = 'manifest_items';
    public $timestamps = false;


    public function company(){
        return $this->belongsTo('App\Models\Company','company_id','id');
    }
}
