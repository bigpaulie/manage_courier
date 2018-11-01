<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

use App\Models\User;
use App\Models\User_profile;
use Illuminate\Support\Str;
use App\Mail\WelcomeStore;
use Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores= User::where('user_type','store')
                      ->OrderBy('created_at','desc')
                      ->paginate(10);

        $data=['stores'=>$stores];
        return view('admin.stores.index',$data);
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
        return view('admin.stores.create',$data);
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
            'phone' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'zip_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('stores.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $random = Str::random(4);
        $random_password = 'store_'.$random;
        $user= new User();
        $user->name = $input['first_name']." ".$input['last_name'];

        $user->password = bcrypt($random_password);
        $user->email = $input['email'];
        $user->user_type = 'store';
        $user->save();
        $user->user_password=$random_password;
        $user_id = $user->id;

        $user_profile= new User_profile();
        $user_profile->user_id = $user_id;
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

        $request->session()->flash('message', 'Store has been added successfully!');
        \Mail::to($user->email)->send(new WelcomeStore($user));
        return redirect()->route('stores.index');
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
        $store = User::find($id);
        $data['countries']=$countries;
        $data['store']=$store;
        $data['states']=State::where('country_id',$store->profile->country_id)->get();
        $data['cities']=City::where('state_id',$store->profile->state_id)->get();

        return view('admin.stores.edit',$data);
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
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
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
            return redirect()->route('stores.edit',$id)
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
        $request->session()->flash('message', 'Store has been updated successfully!');
        return redirect()->route('stores.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        User_profile::where('user_id',$id)->delete();
        $user = User::where('id',$id)->delete();
        \Session::flash('message', 'Store has been deleted successfully!');
        return redirect()->route('stores.index');
    }
}
