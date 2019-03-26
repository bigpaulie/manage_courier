<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manifest_bulk_payment extends Model
{
    protected $table = 'manifest_bulk_payments';
    public $timestamps = false;


    public function company(){
        return $this->belongsTo('App\Models\Company','company_id','id');
    }

    public function manifest(){
        return $this->belongsTo('App\Models\Manifest','manifest_id','id');
    }

}
