<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

use App\Models\User;
use App\Models\User_profile;
use App\Models\Pickup_charge;
use Illuminate\Support\Str;
use App\Mail\WelcomeAgent;
use Validator;



class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $agents= User::where('user_type','agent')
                     ->OrderBy('created_at','desc')
                     ->paginate(10);

        $data=['agents'=>$agents];
        return view('admin.agents.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();

        $data=['countries'=>$countries];
        return view('admin.agents.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'unique_name' => 'required|unique:user_profiles',
            'phone' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agents.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $random = Str::random(4);
        $random_password = 'agent_'.$random;
        $user= new User();
        $user->name = $input['first_name']." ".$input['last_name'];

        $user->password = bcrypt($random_password);
        $user->email = $input['email'];
        $user->user_type = 'agent';
        $user->save();
        $user->user_password=$random_password;
        $user_id = $user->id;

        $user_profile= new User_profile();
        $user_profile->user_id = $user_id;
        $user_profile->unique_name = $input['unique_name'];
        $user_profile->company_name = $input['company_name'];
        $user_profile->first_name = $input['first_name'];
        $user_profile->last_name = $input['last_name'];
        $user_profile->phone = $input['phone'];
        $user_profile->address = $input['address'];
        $user_profile->gender = $input['gender'];
        $user_profile->city_id = $input['city_id'];
        $user_profile->state_id = $input['state_id'];
        $user_profile->country_id = $input['country_id'];
        $user_profile->zip_code = $input['zip_code'];
        $user_profile->save();
        $request->session()->flash('message', 'Agent has been added successfully!');
        \Mail::to($user->email)->send(new WelcomeAgent($user));
        return redirect()->route('agents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $countries = Country::get();
        $agent = User::find($id);
        $data['countries']=$countries;
        $data['agent']=$agent;
       // $data['states']=State::where('country_id',$agent->profile->country_id)->get();
       // $data['cities']=City::where('state_id',$agent->profile->state_id)->get();

        return view('admin.agents.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user_profile_id = $request->user_profile_id;
        $validator = Validator::make($request->all(), [

            'company_name' => 'required',
            'unique_name' => 'required|unique:user_profiles,unique_name,'.$user_profile_id,
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'phone' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('agents.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $user = User::find($id);
        if($user != null){
            $user->email = $input['email'];
            $user->save();
        }
        $user_profile = User_profile::where('user_id',$id)->first();
        if($user_profile != null){
            $user_profile->company_name = $input['company_name'];
            $user_profile->unique_name = $input['unique_name'];
            $user_profile->first_name = $input['first_name'];
            $user_profile->last_name = $input['last_name'];
            $user_profile->phone = $input['phone'];
            $user_profile->address = $input['address'];
            $user_profile->gender = $input['gender'];
            $user_profile->city_id = $input['city_id'];
            $user_profile->state_id = $input['state_id'];
            $user_profile->country_id = $input['country_id'];
            $user_profile->zip_code = $input['zip_code'];
            $user_profile->save();
        }
        $request->session()->flash('message', 'Agent has been updated successfully!');
        return redirect()->route('agents.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pickup_charge::where('user_id',$id)->delete();
        User_profile::where('user_id',$id)->delete();
        $user = User::where('id',$id)->delete();
        \Session::flash('message', 'Agent has been deleted successfully!');
        return redirect()->route('agents.index');
    }
}
