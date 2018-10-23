<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class UserController extends Controller
{
    //
    public function admin_dashboard(){

        $data=[];
        return view('admin.dashboard',$data);
    }

    public function agent_dashboard(){

        $data=[];
        return view('agent.dashboard',$data);
    }

    public function store_dashboard(){

        $data=[];
        return view('store.dashboard',$data);
    }

    public function profile($id){

        $profile = User::find($id);
        $countries = Country::get();
        $data['countries']=$countries;
        $data['states']=State::where('country_id',$profile->profile->country_id)->get();
        $data['cities']=City::where('state_id',$profile->profile->state_id)->get();
        $data['profile']=$profile;
        return view('users.profile',$data);
    }
}
