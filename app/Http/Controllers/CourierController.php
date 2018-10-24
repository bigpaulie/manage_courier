<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Package_type;
use App\Models\Content_type;
use App\Models\Service_type;
use App\Models\Courier;
use App\Models\Shippment;
use App\Models\Status;
use App\Models\Notification;

use Validator;


class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = \Auth::user()->user_type;
        if($user_type == 'admin'){
            $couriers= Courier::paginate(10);
        }else if($user_type == 'agent'){
            $couriers= Courier::where('user_id',\Auth::user()->id)->paginate(10);
        }

        $data['couriers']=$couriers;
        $data['status']=Status::pluck('name','id')->toArray();
        return view('couriers.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        $data['countries']=$countries;
        $data['package_types']=Package_type::pluck('name', 'id')->toArray();
        $data['content_types']=Content_type::pluck('name', 'id')->toArray();
        $data['service_types']=Service_type::pluck('name', 'id')->toArray();
        return view('couriers.create',$data);
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
            's_name' => 'required',
            's_company' => 'required',
            's_address1' => 'required',
            's_phone' => 'required',
            's_country' => 'required',
            's_state' => 'required',
            's_city' => 'required',
            's_email' => 'email',
            'r_name' => 'required',
            'r_company' => 'required',
            'r_address1' => 'required',
            'r_phone' => 'required',
            'r_country'=>'required',
            'r_state'=>'required',
            'r_city'=>'required',
            'r_email' => 'email',
        ]);

        if ($validator->fails()) {

            return redirect('/'.\Auth::user()->user_type.'/couriers/create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $input['user_id']= \Auth::user()->id;
        $status = Status::where('code_name','courier_confirmed')->first();
        $input['status_id']=$status->id;
        $courier = Courier::create($input);
        $courier_id = $courier->id;

        $shippment = new Shippment();
        $shippment->courier_id = $courier_id;
        $shippment->package_type_id = $input['package_type_id'];
        $shippment->service_type_id = $input['service_type_id'];
        $shippment->content_type_id = $input['content_type_id'];
        $shippment->weight = $input['weight'];
        $shippment->carriage_value = $input['carriage_value'];
        $shippment->save();

        if(\Auth::user()->user_type == 'agent'){
            $notification = new Notification();
            $notification->user_id = \Auth::user()->id;
            $notification->notification_type = 'Added New Courier';
            $notification->message = 'Courier has been added';
            $notification->status = 'unread';
            $notification->save();
        }

        $request->session()->flash('message', 'Courier has been added successfully!');

        return redirect('/'.\Auth::user()->user_type.'/couriers');
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
        $courier= Courier::find($id);

        $data['countries']=$countries;
        $data['package_types']=Package_type::pluck('name', 'id')->toArray();
        $data['content_types']=Content_type::pluck('name', 'id')->toArray();
        $data['service_types']=Service_type::pluck('name', 'id')->toArray();
        $data['s_states']=State::where('country_id',$courier->s_country)->get();
        $data['s_cities']=City::where('state_id',$courier->s_state)->get();
        $data['r_states']=State::where('country_id',$courier->r_country)->get();
        $data['r_cities']=City::where('state_id',$courier->r_state)->get();
        $data['courier']=$courier;

        return view('couriers.edit',$data);
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
            's_name' => 'required',
            's_company' => 'required',
            's_address1' => 'required',
            's_phone' => 'required',
            's_country' => 'required',
            's_state' => 'required',
            's_city' => 'required',
            's_email' => 'email',
            'r_name' => 'required',
            'r_company' => 'required',
            'r_address1' => 'required',
            'r_phone' => 'required',
            'r_country'=>'required',
            'r_state'=>'required',
            'r_city'=>'required',
            'r_email' => 'email',
        ]);

        if ($validator->fails()) {

            return redirect('/'.\Auth::user()->user_type.'/couriers/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }



        $input = $request->all();

        $courier = Courier::find($id);
        $courier->s_name = $input['s_name'];
        $courier->s_company = $input['s_company'];
        $courier->s_address1 = $input['s_address1'];
        $courier->s_address2 = $input['s_address2'];
        $courier->s_phone = $input['s_phone'];
        $courier->s_country = $input['s_country'];
        $courier->s_state = $input['s_state'];
        $courier->s_city = $input['s_city'];
        $courier->s_email = $input['s_email'];
        $courier->r_name = $input['r_name'];
        $courier->r_company = $input['r_company'];
        $courier->r_address1 = $input['r_address1'];
        $courier->r_address2 = $input['r_address2'];
        $courier->r_phone = $input['r_phone'];
        $courier->r_country = $input['r_country'];
        $courier->r_state = $input['r_state'];
        $courier->r_city = $input['r_city'];
        $courier->r_email = $input['r_email'];
        $courier->save();

        $shippment = Shippment::where('courier_id',$id)->first();
        $shippment->package_type_id = $input['package_type_id'];
        $shippment->service_type_id = $input['service_type_id'];
        $shippment->content_type_id = $input['content_type_id'];
        $shippment->weight = $input['weight'];
        $shippment->carriage_value = $input['carriage_value'];
        $shippment->save();
        $request->session()->flash('message', 'Courier has been updated successfully!');
        return redirect('/'.\Auth::user()->user_type.'/couriers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Shippment::where('courier_id',$id)->delete();
        Courier::where('id',$id)->delete();
        \Session::flash('message', 'Courier has been deleted successfully!');
        return redirect('/'.\Auth::user()->user_type.'/couriers');
    }

    public function updateCourierStatus(Request $request){
        $input =$request->all();
        $courier = Courier::find($input['courier_id']);
        $courier->status_id = $input['status_id'];
        $courier->save();

    }
}
