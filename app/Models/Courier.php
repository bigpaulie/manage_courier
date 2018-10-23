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

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'status_id', 'tracking_no','s_name',
        's_company', 's_address1', 's_address2','s_phone',
        's_country', 's_state', 's_city','s_email',
        'r_name', 'r_company', 'r_address1','r_address2',
        'r_phone', 'r_country', 'r_state','r_city','r_email',
        'description'
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
}
