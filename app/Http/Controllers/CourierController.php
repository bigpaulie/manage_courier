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
use App\Models\User;
use App\Models\Courier_service;
use App\Models\Courier_charge;
use App\Models\Payment;





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

        $data['status']=Status::all();
        $data['accepted_status_id']=Status::where('code_name',"accepted")->first()->id;
        $data['shipped_status_id']=Status::where('code_name',"shipped")->first()->id;
        $data['courier_companies']=Courier_service::pluck('name','id')->toArray();
        if(\Auth::user()->user_type == 'agent'){
            $data['total_charge']= Courier_charge::where('user_id',\Auth::user()->id)->sum('total');
            $data['total_payout']= Payment::where('user_id',\Auth::user()->id)->sum('amount');
            $data['grand_total']= $data['total_payout'] - $data['total_charge'];

        }
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
        $user_id = \Auth::user()->id;
        $user_data = User::with('profile')->find($user_id);
        $data['user_data']= $user_data;
        $data['s_states']=State::where('country_id',$user_data->profile->country_id)->get();
        $data['s_cities']=City::where('state_id',$user_data->profile->state_id)->get();
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
            //'s_phone' => 'required',
            's_country' => 'required',
            's_state' => 'required',
            's_city' => 'required',
           // 's_zip_code' => 'required',
            'r_name' => 'required',
            'r_company' => 'required',
            'r_address1' => 'required',
           // 'r_phone' => 'required',
            'r_country'=>'required',
            'r_state'=>'required',
            'r_city'=>'required',
            //'r_zip_code' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect('/'.\Auth::user()->user_type.'/couriers/create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();

        $input['user_id']= \Auth::user()->id;
        $status = Status::where('code_name','pending')->first();
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
        $shippment->courier_status = isset($input['courier_status'])?$input['courier_status']:"drop";
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
           // 's_phone' => 'required',
            's_country' => 'required',
            's_state' => 'required',
            's_city' => 'required',
           // 's_zip_code' => 'required',
            'r_name' => 'required',
            'r_company' => 'required',
            'r_address1' => 'required',
           // 'r_phone' => 'required',
            'r_country'=>'required',
            'r_state'=>'required',
            'r_city'=>'required',
            //'r_zip_code' => 'required',
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
       // $courier->s_zip_code = $input['s_zip_code'];
        $courier->r_name = $input['r_name'];
        $courier->r_company = $input['r_company'];
        $courier->r_address1 = $input['r_address1'];
        $courier->r_address2 = $input['r_address2'];
        $courier->r_phone = $input['r_phone'];
        $courier->r_country = $input['r_country'];
        $courier->r_state = $input['r_state'];
        $courier->r_city = $input['r_city'];
        $courier->r_email = $input['r_email'];
        $courier->r_zip_code = $input['r_zip_code'];
        $courier->save();

        $shippment = Shippment::where('courier_id',$id)->first();
        $shippment->package_type_id = $input['package_type_id'];
        $shippment->service_type_id = $input['service_type_id'];
        $shippment->content_type_id = $input['content_type_id'];
        $shippment->weight = $input['weight'];
        $shippment->carriage_value = $input['carriage_value'];
        $shippment->courier_status = isset($input['courier_status'])?$input['courier_status']:"drop";
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

    public function getCouriers(Request $request){

        $input = $request->all();
        $type = isset($input['type'])?$input['type']:'all';
        $traking_number = isset($input['traking_number'])?$input['traking_number']:'';
        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        $status_id = isset($input['status_id'])?$input['status_id']:'';
        $agent_name = isset($input['agent_name'])?$input['agent_name']:'';
        $user_id= $input['user_id'];
        $user_type = $input['user_type'];


        if($type == 'all'){
            $where = [];
            if($user_type == 'agent'){
                $where[] = ['couriers.user_id', $user_id];
            }
            $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                ->whereDate('updated_at','>=', date('Y-m-d'))
                ->whereDate('updated_at', '<=',date('Y-m-d'))
                ->where($where)
                ->OrderBy('updated_at','desc');
        }else{
            $where = [];
            if($user_type == 'agent'){
                $where[] = ['couriers.user_id', $user_id];
            }
            if ($traking_number !="" ) {
                $where[] = ['couriers.tracking_no', $traking_number];
                $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                                    ->where($where);
            }
            if ($status_id !="" ) {
                $where[] = ['couriers.status_id', $status_id];

                $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                                    ->where($where);
            }
            if ($agent_name !="" ) {

                $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                                    ->where($where)
                                    ->whereHas('agent',function ($query) use($agent_name){
                                        $query->where('name', 'like', "%{$agent_name}%");
                                    });
            }

            if($from_date !="" && $end_date != ""){

                $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                                    ->whereDate('updated_at','>=', $from_date)
                                    ->whereDate('updated_at', '<=',$end_date)
                                    ->where($where);
            }

        }
        $courier_data = $couriers->paginate(15);

        // dd($courier_data->total());
        $total_amount=0;
        $total_pickup_charge=0;
        $total=0;
         if($courier_data->total() > 0 ){
                foreach ($courier_data as $c_data){
                    if($c_data->courier_charge != null){
                        $total_amount+=$c_data->courier_charge->amount;
                        $total_pickup_charge+=$c_data->courier_charge->pickup_charge;
                        $total+=$c_data->courier_charge->total;
                    }
                }
         }
         $response_data['total_amount']=$total_amount;
         $response_data['total_pickup_charge']=$total_pickup_charge;
         $response_data['total']=$total;
         $response_data['courier_data']=$courier_data;

        return response()->json($response_data);
        //return $couriers;
    }

    public function createCourierCsv(Request $request){

        $current_timestamp = date("Y-m-d-H-i-s")."_courier";
        $file_path = storage_path()."/".$current_timestamp.'.csv';
        $writer = \CsvWriter::create($file_path);
        $writer->writeLine(['Courier Id','Agent Name', 'Status', 'Tracking No','Sender Name',
                             'Sender Company', 'Sender Address1','Sender Address2',
                             'Sender Company', 'Sender Address1','Sender Address2',
                             'Sender Phone', 'Sender Country','Sender State',
                             'Sender City', 'Sender Email','Receiver Name',
                             'Receiver Company', 'Receiver Address1','Receiver Address2',
                             'Receiver Phone', 'Receiver Country','Receiver State',
                             'Receiver Email', 'Description','Created',
                            ]);

        $input = $request->all();
        $type = isset($input['type'])?$input['type']:'all';
        $traking_number = isset($input['traking_number'])?$input['traking_number']:'';
        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        $status_id = isset($input['status_id'])?$input['status_id']:'';
        $agent_name = isset($input['agent_name'])?$input['agent_name']:'';
        $user_id= $input['user_id'];
        $user_type = $input['user_type'];


        if($type == 'all'){
            $where = [];
            if($user_type == 'agent'){
                $where[] = ['couriers.user_id', $user_id];
            }
            $couriers= Courier::with(['agent','status','shippment'])
                                ->where('status_id',1)
                                ->where($where);
        }else{
            $where = [];
            if($user_type == 'agent'){
                $where[] = ['couriers.user_id', $user_id];
            }
            if ($traking_number !="" ) {
                $where[] = ['couriers.tracking_no', $traking_number];
                $couriers= Courier::with(['agent','status','shippment'])
                    ->where($where);
            }
            if ($status_id !="" ) {
                $where[] = ['couriers.status_id', $status_id];

                $couriers= Courier::with(['agent','status','shippment'])
                    ->where($where);
            }
            if ($agent_name !="" ) {

                $couriers= Courier::with(['agent','status','shippment'])
                    ->where($where)
                    ->whereHas('agent',function ($query) use($agent_name){
                        $query->where('name', 'like', "%{$agent_name}%");
                    });
            }

            if($from_date !="" && $end_date != ""){

                $couriers= Courier::with(['agent','status','shippment'])
                    ->whereDate('updated_at','>=', $from_date)
                    ->whereDate('updated_at', '<=',$end_date)
                    ->where($where);
            }

        }
        $records = $couriers->paginate(15);

        foreach ($records as $courier){

            $s_country = ($courier->sender_country != null)?$courier->sender_country->name:"";
            $s_state = ($courier->sender_state != null)?$courier->sender_state->state_name:"";
            $s_city = ($courier->sender_city != null)?$courier->sender_city->city_name:"";
            $r_country = ($courier->receiver_country != null)?$courier->receiver_country->name:"";
            $r_state = ($courier->receiver_state != null)?$courier->receiver_state->state_name:"";
            $r_city = ($courier->receiver_city != null)?$courier->receiver_city->city_name:"";
            $temp = [
                $courier->id,
                $courier->agent->name,
                $courier->status->name,
                $courier->tracking_no,
                $courier->s_name,
                $courier->s_company,
                $courier->s_address1,
                $courier->s_address2,
                $courier->s_phone,
                $s_country,
                $s_state,
                $s_city,
                $courier->s_email,
                $courier->r_name,
                $courier->r_company,
                $courier->r_address1,
                $courier->r_address2,
                $courier->r_phone,
                $r_country,
                $r_state,
                $r_city,
                $courier->r_email,
                $courier->description,
                $courier->created_at,

            ];
            $writer->writeLine($temp);
        }

        $writer->close();
        return response()->download($file_path)->deleteFileAfterSend(true);

    }

    public function saveCourierCharge(Request $request){
        $input = $request->all();
        $status_code_name = $input['status_code_name'];
        $courier_id = $input['courier_id'];
        $user_id = $input['user_id'];
        $courier = Courier::find($courier_id);
        if($courier !=null){
            //$courier->weight = $input['weight'];
            $courier->status_id = $input['status_id'];
            if($status_code_name == 'shipped'){
                $courier->tracking_no = $input['tracking_number'];
            }
            $courier->save();

            $courier_charge = Courier_charge::where('courier_id',$courier_id)
                                              ->where('user_id',$user_id)->first();
            if($courier_charge != null){

                if($status_code_name == 'accepted') {

                    $courier_charge->amount = $input['amount'];
                    if ($input['is_pickup'] == 'pickup') {
                        $courier_charge->pickup_charge = $input['pickup_charge'];
                        $courier_charge->is_pickup = 1;
                    }
                    $courier_charge->total = $input['total_charge'];
                }
                if($status_code_name == 'shipped') {
                    $courier_charge->courier_service_id = $input['dispatch_through'];
                    $courier_charge->delivery_date = date('Y-m-d',strtotime($input['delivery_date']));
                }
                $courier_charge->save();

            }else{


                $Courier_charge = new Courier_charge();
                $Courier_charge->courier_id = $courier_id;
                $Courier_charge->user_id = $input['user_id'];
                if($status_code_name == 'accepted') {

                    $Courier_charge->amount = $input['amount'];
                    if ($input['is_pickup'] == 'pickup') {
                        $Courier_charge->pickup_charge = $input['pickup_charge'];
                        $Courier_charge->is_pickup = 1;
                    }
                    $Courier_charge->total = $input['total_charge'];
                }
                if($status_code_name == 'shipped') {
                    $Courier_charge->courier_service_id = $input['dispatch_through'];
                    $Courier_charge->delivery_date = date('Y-m-d',strtotime($input['delivery_date']));
                }
                $Courier_charge->save();

            }


        }
    }
}
