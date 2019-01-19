<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    //use SoftDeletes;
    //protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id','unique_name', 'status_id', 'tracking_no','barcode_no',
        's_name',
        's_company', 's_address1', 's_address2','s_phone',
        's_country', 's_state', 's_city','s_email','s_zip_code',
        'r_name', 'r_company', 'r_address1','r_address2',
        'r_phone', 'r_country', 'r_state','r_city','r_email','r_zip_code',
        'no_of_boxes','description','courier_date'
    ];

    public function agent(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function status(){
        return $this->belongsTo('App\Models\Status','status_id','id');
    }

    public function shippment(){
        return $this->hasOne('App\Models\Shippment');
    }

    public function courier_charge(){
        return $this->hasOne('App\Models\Courier_charge')->with('courier_service');
    }

    public function sender_country(){
        return $this->belongsTo('App\Models\Country','s_country','id');
    }

    public function sender_state(){
        return $this->belongsTo('App\Models\State','s_state','id');
    }

    public function sender_city(){
        return $this->belongsTo('App\Models\City','s_city','id');
    }

    public function receiver_country(){
        return $this->belongsTo('App\Models\Country','r_country','id');
    }

    public function receiver_state(){
        return $this->belongsTo('App\Models\State','r_state','id');
    }

    public function receiver_city(){
        return $this->belongsTo('App\Models\City','r_city','id');
    }
    public function courier_boxes(){
        return $this->hasMany('App\Models\Courier_box','courier_id')->with('courier_box_items');
    }

    public function courier_payment(){
        return $this->hasOne('App\Models\Courier_payment');
    }

}
