<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;


class MasterController extends Controller
{

    public function getStates(Request $request){

        $country_id = $request->get('country_id');
        $states = State::where('country_id',$country_id)->get();
        return $states;
    }

    public function getCities(Request $request){

        $state_id = $request->get('state_id');
        $cities = City::where('state_id',$state_id)->get();
        return $cities;
    }
}
