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
use App\Models\Courier_box;
use App\Models\Courier_box_item;
use App\Exports\CourierExport;
use App\Models\Courier_payment;
use App\Models\User_profile;
use App\Models\Manifest;



use Maatwebsite\Excel\Facades\Excel;
use Validator;


class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data['status']=Status::all();
        $data['accepted_status_id']=Status::where('code_name',"accepted")->first()->id;
        $data['shipped_status_id']=Status::where('code_name',"shipped")->first()->id;
        $data['courier_companies']=Courier_service::pluck('name','id')->toArray();
        if(\Auth::user()->user_type == 'agent'){
            $total_amount = Courier_payment::where('user_id',\Auth::user()->id)->sum('total');
            $paid_amount = Payment::where('user_id',\Auth::user()->id)->sum('amount');
            $data['total_amount']=$total_amount;
            $data['paid_amount']=$paid_amount;
            $data['remaining']=$total_amount-$paid_amount;
        }
        $from_date = date('m/d/Y');
        $end_date=date('m/d/Y');
        $setFilter=0;
        $status_id = "";
        $traking_number="";
        $agent_name="";
        $agent_full_name="";
        if($request->session()->has('from_date'))
        {
            $from_date =$request->session()->get('from_date');
            $setFilter=1;
        }

        if($request->session()->has('end_date'))
        {
            $end_date =$request->session()->get('end_date');
            $setFilter=1;
        }

        if($request->session()->has('status_id'))
        {
            $status_id =$request->session()->get('status_id');
            $setFilter=1;
        }

        if($request->session()->has('traking_number'))
        {
            $traking_number =$request->session()->get('traking_number');
            $setFilter=1;
        }

        if($request->session()->has('agent_name'))
        {
            $agent_name =$request->session()->get('agent_name');
            if(!empty($agent_name) && $agent_name > 0){
                $user_data = User::find($agent_name);
                $agent_full_name = $user_data->name;
            }

            $setFilter=1;
        }
        $data['from_date']=$from_date;
        $data['end_date']=$end_date;
        $data['setFilter']=$setFilter;
        $data['status_id']=$status_id;
        $data['traking_number']=$traking_number;
        $data['agent_name']=$agent_name;
        $data['agent_full_name']=$agent_full_name;
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
        $data['courier_unique_no'] = $this->getCourierUniqueName(\Auth::user()->id);
        $courier_payment = new Courier_payment();
        $courier_payment->total=null;
        $courier_payment->pay_amount=0;
        $courier_payment->remaining=0;
        $courier_payment->discount=0;
        $courier_payment->payment_date=date('Y-m-d');
        $data['courier_payment']=$courier_payment;

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
           // 's_zip_code' => 'required',
            'r_name' => 'required',
            'r_company' => 'required',
            'r_address1' => 'required',
           // 'r_phone' => 'required',
            'r_country'=>'required',
            'r_state'=>'required',
            'r_city'=>'required',
            //'r_zip_code' => 'required',
           'payment_date' => 'required',
          // 'pay_amount' => 'required',
           'total' => 'required',
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
        $input['unique_name']=$this->getCourierUniqueName(\Auth::user()->id);
        $input['barcode_no']= rand();
        $input['courier_date']=date('Y-m-d',strtotime($input['courier_date']));
        $courier = Courier::create($input);
        $courier_id = $courier->id;

        $shippment = new Shippment();
        $shippment->courier_id = $courier_id;
        $shippment->package_type_id = $input['package_type_id'];
        $shippment->service_type_id = $input['service_type_id'];
        //$shippment->content_type_id = $input['content_type_id'];
        $shippment->weight = $input['weight'];
        $shippment->carriage_value = $input['carriage_value'];
       // $shippment->courier_status = isset($input['courier_status'])?$input['courier_status']:"drop";
        $shippment->save();

        $couier_payment = new Courier_payment();
        $couier_payment->courier_id = $courier_id;
        $couier_payment->user_id = \Auth::user()->id;
        $couier_payment->total= $input['total'];
        if( \Auth::user()->user_type != 'agent') {
            $couier_payment->pay_amount = $input['pay_amount'];
            $couier_payment->remaining = $input['remaining'];
            $couier_payment->discount = $input['discount'];
        }
        $couier_payment->payment_date= date('Y-m-d',strtotime($request->payment_date));
        $couier_payment->save();

        if(\Auth::user()->user_type == 'agent'){
            $notification = new Notification();
            $notification->user_id = \Auth::user()->id;
            $notification->notification_type = 'Added New Courier';
            $notification->message = 'Courier has been added';
            $notification->status = 'unread';
            $notification->save();
        }

        $request->session()->flash('message', 'Courier has been added successfully!');

        return redirect('/'.\Auth::user()->user_type.'/couriers/box_details/'.$courier_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $courier= Courier::find($id);
        if($courier != null){
            $data['courier']=$courier;

            $courier_manifest_data = \DB::table("manifest")
                         ->whereRaw('FIND_IN_SET(?,courier_ids)', [$id])
                         ->first();
            if($courier_manifest_data != null){
                $manifest_data = Manifest::find($courier_manifest_data->id);
                $data['manifest_data']=$manifest_data;
            }

            return view('couriers.show',$data);
        }else{
            abort(404);
        }

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
//        $data['s_states']=State::where('country_id',$courier->s_country)->get();
//        $data['s_cities']=City::where('state_id',$courier->s_state)->get();
//        $data['r_states']=State::where('country_id',$courier->r_country)->get();
//        $data['r_cities']=City::where('state_id',$courier->r_state)->get();
        $data['courier']=$courier;
        $data['status']=Status::all();
        $courier_payment = Courier_payment::where('courier_id',$id)
                        ->first();

        if($courier_payment != null){
            if(empty($courier_payment->total_amount))
            {
                $courier_payment->total_amount=0;
            }

            if(empty($courier_payment->pay_amount))
            {
                $courier_payment->pay_amount=0;
            }

            if(empty($courier_payment->remaining))
            {
                $courier_payment->remaining=0;
            }

            if(empty($courier_payment->discount))
            {
                $courier_payment->discount=0;
            }

            $data['courier_payment']=$courier_payment;

        }else{
            $courier_payment = new Courier_payment();
            $courier_payment->total=null;
            $courier_payment->pay_amount=0;
            $courier_payment->remaining=0;
            $courier_payment->discount=0;
            $courier_payment->payment_date=date('Y-m-d');
            $data['courier_payment']=$courier_payment;

        }




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
        $courier->courier_date=date('Y-m-d',strtotime($input['courier_date']));
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
        $courier->status_id = $input['status_id'];
        $courier->tracking_no = $input['tracking_no'];
        $courier->save();

        $shippment = Shippment::where('courier_id',$id)->first();
        $shippment->package_type_id = $input['package_type_id'];
        $shippment->service_type_id = $input['service_type_id'];
        //$shippment->content_type_id = $input['content_type_id'];
        $shippment->weight = $input['weight'];
        $shippment->carriage_value = $input['carriage_value'];
       // $shippment->courier_status = isset($input['courier_status'])?$input['courier_status']:"drop";
        $shippment->save();


        $input['courier_id']=$id;
        $input['user_id']=\Auth::user()->id;
        $input['payment_date']=date('Y-m-d',strtotime($request->payment_date));
        $couier_payment = Courier_payment::where('courier_id',$id)->first();
        if($couier_payment != null){
            $couier_payment->id = $couier_payment->id;
            $couier_payment->total= $input['total'];
            if( \Auth::user()->user_type != 'agent') {
                $couier_payment->pay_amount = $input['pay_amount'];
                $couier_payment->remaining = $input['remaining'];
                $couier_payment->discount = $input['discount'];
            }
            $couier_payment->payment_date= date('Y-m-d',strtotime($request->payment_date));
            $couier_payment->save();
        }else{
            Courier_payment::create($input);
        }


        $request->session()->flash('message', 'Courier has been updated successfully!');
        return redirect('/'.\Auth::user()->user_type.'/couriers/box_details/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Courier_box::where('courier_id',$id)->delete();
        Courier_box_item::where('courier_id',$id)->delete();
        Courier_payment::where('courier_id',$id)->delete();
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


        $request->session()->put('from_date', $request->from_date);
        $request->session()->put('end_date', $request->end_date);
        $request->session()->put('status_id', $status_id);
        $request->session()->put('traking_number', $traking_number);
        $request->session()->put('agent_name', $agent_name);


        $courier_joins = Courier::with(['agent','status','shippment','courier_payment','receiver_country']);
        if($type == 'all'){

            if($user_type == 'agent') {
                $user_ids = [$user_id];
                $couriers = $courier_joins
                    ->whereDate('courier_date', '>=', $from_date)
                    ->whereDate('courier_date', '<=', $end_date)
                    ->whereIn('couriers.user_id', $user_ids)
                    ->OrderBy('courier_date', 'desc');
            }
            elseif($user_type == 'store'){
                $user_ids = User_profile::where('store_id',$user_id)->pluck('user_id')->toArray();
                array_push($user_ids,[$user_id]);
                $couriers= $courier_joins
                    ->whereDate('courier_date','>=', $from_date)
                    ->whereDate('courier_date', '<=',$end_date)
                    ->whereIn('couriers.user_id',$user_ids)
                    ->OrderBy('courier_date','desc');

            }else{

                $couriers= $courier_joins
                                    ->whereDate('courier_date','>=', $from_date)
                                    ->whereDate('courier_date', '<=',$end_date)
                                    ->OrderBy('courier_date','desc');
            }


        }else{
            $where = [];
            if($user_type == 'agent' ){
                $where[] = ['couriers.user_id', $user_id];
            }
            if($user_type == 'store'){

                $user_ids = User_profile::where('store_id',$user_id)->pluck('user_id')->toArray();
                array_push($user_ids,[$user_id]);
                $couriers=  $courier_joins
                                 ->whereIn('couriers.user_id',$user_ids);
            }

            if ($traking_number !="" ) {
                $where[] = ['couriers.tracking_no', $traking_number];
                $couriers=  $courier_joins
                                    ->where($where);
            }
            if ($status_id !="" ) {
                $where[] = ['couriers.status_id', $status_id];

                $couriers= $courier_joins
                                    ->where($where);
            }
            if ($agent_name > 0 ) {

                $couriers= $courier_joins
                                    ->where($where)
                                    ->where('user_id',$agent_name);
            }

            if($from_date !="" && $end_date != ""){

                $couriers= $courier_joins
                    ->whereDate('courier_date','>=', $from_date)
                    ->whereDate('courier_date', '<=',$end_date)
                    ->where($where);
            }

        }
        $courier_data = $couriers->paginate(50);


        $total_paid_amount=0;
        $total_remaining=0;
        $total=0;
         if($courier_data->total() > 0 ){
                foreach ($courier_data as $c_data){
                    if($c_data->courier_payment != null){
                        $total_paid_amount+=$c_data->courier_payment->pay_amount;
                        $total_remaining+=$c_data->courier_payment->remaining;
                        $total+=$c_data->courier_payment->total;
                    }
                }
         }
         $response_data['total_paid_amount']=$total_paid_amount;
         $response_data['total_remaining']=$total_remaining;
         $response_data['total']=$total;
         $response_data['courier_data']=$courier_data;

        return response()->json($response_data);
        //return $couriers;
    }

    public function createCourierCsv(Request $request){

        $current_timestamp = date("Y-m-d-H-i-s")."_courier";
        $file_path = storage_path()."/".$current_timestamp.'.csv';
        $writer = \CsvWriter::create($file_path);
        $writer->writeLine(['Courier Id',
                            'Unique Name',
                            'Agent Name',
                            'Status',
                            'Tracking No',
                            'Tracking URL',
                            'Total Amount',
                            'Paid Amount',
                            'Remaining Amount',
                            'Discount',
                            'Total Weight',
                            'No of Boxes',
                            'Carriage Value',
                            'Package Type',
                            'Service Type',
                            'Sender Name',
                            'Sender Company', 'Sender Address1','Sender Address2',
                             'Sender Phone', 'Sender Country','Sender State',
                             'Sender City', 'Sender Email','Receiver Name',
                             'Receiver Company', 'Receiver Address1','Receiver Address2',
                             'Receiver Phone', 'Receiver Country','Receiver State',
                             'Receiver City','Receiver Zipcode',
                             'Receiver Email', 'Description','Created',
                            ]);

        $package_types=Package_type::pluck('name', 'id')->toArray();
        $service_types=Service_type::pluck('name', 'id')->toArray();

        $input = $request->all();
        $type = isset($input['type'])?$input['type']:'all';
        $traking_number = isset($input['traking_number'])?$input['traking_number']:'';
        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        $status_id = isset($input['status_id'])?$input['status_id']:'';
        $agent_name = isset($input['agent_name'])?$input['agent_name']:'';
        $user_id= $input['user_id'];
        $user_type = $input['user_type'];

        $courier_joins = Courier::with(['agent','status','shippment','courier_payment','receiver_country','sender_country']);
        if($type == 'all'){

            if($user_type == 'agent') {
                $user_ids = [$user_id];
                $couriers = $courier_joins
                    ->whereDate('courier_date', '>=', date('Y-m-d'))
                    ->whereDate('courier_date', '<=', date('Y-m-d'))
                    ->whereIn('couriers.user_id', $user_ids)
                    ->OrderBy('courier_date', 'desc');
            }
            elseif($user_type == 'store'){
                $user_ids = User_profile::where('store_id',$user_id)->pluck('user_id')->toArray();
                array_push($user_ids,[$user_id]);
                $couriers= $courier_joins
                    ->whereDate('courier_date','>=', date('Y-m-d'))
                    ->whereDate('courier_date', '<=',date('Y-m-d'))
                    ->whereIn('couriers.user_id',$user_ids)
                    ->OrderBy('courier_date','desc');

            }else{

                $couriers= $courier_joins
                    ->whereDate('courier_date','>=', date('Y-m-d'))
                    ->whereDate('courier_date', '<=',date('Y-m-d'))
                    ->OrderBy('courier_date','desc');
            }


        }else{
            $where = [];
            if($user_type == 'agent' ){
                $where[] = ['couriers.user_id', $user_id];
            }
            if($user_type == 'store'){

                $user_ids = User_profile::where('store_id',$user_id)->pluck('user_id')->toArray();
                array_push($user_ids,[$user_id]);
                $couriers=  $courier_joins
                    ->whereIn('couriers.user_id',$user_ids);
            }

            if ($traking_number !="" ) {
                $where[] = ['couriers.tracking_no', $traking_number];
                $couriers=  $courier_joins
                    ->where($where);
            }
            if ($status_id !="" ) {
                $where[] = ['couriers.status_id', $status_id];

                $couriers= $courier_joins
                    ->where($where);
            }
            if ($agent_name > 0 ) {

                $couriers= $courier_joins
                    ->where($where)
                    ->where('user_id',$agent_name);
            }

            if($from_date !="" && $end_date != ""){

                $couriers= $courier_joins
                    ->whereDate('courier_date','>=', $from_date)
                    ->whereDate('courier_date', '<=',$end_date)
                    ->where($where);
            }

        }
        $courier_data = $couriers->get();

        foreach ($courier_data as $courier){

            $s_country = ($courier->sender_country != null)?$courier->sender_country->name:"";
            $s_state = ($courier->s_state != null)?$courier->s_state:"";
            $s_city = ($courier->s_city != null)?$courier->s_city:"";
            $r_country = ($courier->receiver_country != null)?$courier->receiver_country->name:"";
            $r_state = ($courier->r_state != null)?$courier->r_state:"";
            $r_city = ($courier->r_city != null)?$courier->r_city:"";
            $total_amount=0;
            $paid_amount=0;
            $remaining_amount=0;
            $discount=0;

            if($courier->courier_payment != null){

                $total_amount = $courier->courier_payment->total;
                $paid_amount = $courier->courier_payment->pay_amount;
                $remaining_amount = $courier->courier_payment->remaining;
                $discount = $courier->courier_payment->discount;

            }
            $tracking_url = 'NA';
            if(!empty($courier->tracking_url)){
                $tracking_url = $courier->tracking_url;
            }

            $temp = [
                $courier->id,
                $courier->unique_name,
                $courier->agent->name,
                $courier->status->name,
                $courier->tracking_no,
                $tracking_url,
                $total_amount,
                $paid_amount,
                $remaining_amount,
                $discount,
                $courier->shippment->weight,
                $courier->no_of_boxes,
                $courier->shippment->carriage_value,
                $package_types[$courier->shippment->package_type_id],
                $service_types[$courier->shippment->service_type_id],
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
                $courier->r_zip_code,
                $courier->r_email,
                $courier->description,
                $courier->courier_date,

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

    public function importCourierCSv(Request $request){

        if($request->file('courier_csv'))
        {
            $path = $request->file('courier_csv')->getRealPath();
            $reader = \CsvReader::open($path);
            $i=0;

            $package_types=Package_type::pluck('id', 'name')->toArray();
            $service_types=Service_type::pluck('id', 'name')->toArray();

            while (($line = $reader->readLine()) !== false) {
               if($i >0 ){

                   $courier_id = $line[0];
                   $unique_name = $line[1];
                   $agent_name = $line[2];
                   $status = $line[3];
                   $tracking_no = $line[4];
                   $tracking_url = $line[5];
                   $total_amount = $line[6];
                   $paid_amount = $line[7];
                   $remaining_amount = $line[8];
                   $discount = $line[9];
                   $weight= $line[10];
                   $no_of_boxes= $line[11];
                   $carriage_value = $line[12];
                   $package_type = $line[13];
                   $service_type = $line[14];
                   $s_name = $line[15];
                   $s_company = $line[16];
                   $s_address1 = $line[17];
                   $s_address2 = $line[18];
                   $s_phone = $line[19];
                   $s_country = $line[20];
                   $s_state = $line[21];
                   $s_city = $line[22];
                   $s_email = $line[23];
                   $r_name = $line[24];
                   $r_comapny = $line[25];
                   $r_address1 = $line[26];
                   $r_address2 = $line[27];
                   $r_phone = $line[28];
                   $r_country = $line[29];
                   $r_state = $line[30];
                   $r_city = $line[31];
                   $r_zip_code = $line[32];
                   $r_email = $line[33];
                   $description = $line[34];
                   $courier = Courier::find($courier_id);

                   if($courier != null){
                        //update the record
                       $status_data = Status::where('name',$status)->first();
                       if($status_data !=null){
                           $courier->status_id = $status_data->id;
                       }
                       $courier->tracking_no = $tracking_no;

                       if(!empty($tracking_url) && $tracking_url != 'NA'){
                           $courier->tracking_url = $tracking_url;
                       }

                       $courier->s_name = $s_name;
                       $courier->s_company = $s_company;
                       $courier->s_address1 = $s_address1;
                       $courier->s_address2 = $s_address2;
                       $courier->s_phone = $s_phone;
                       $courier->no_of_boxes = $no_of_boxes;

                       if(!empty($s_country)){
                         $country_data = Country::where('name',$s_country)->first();
                         if($country_data != null){
                             $courier->s_country = $country_data->id;
                         }
                       }

                       if(!empty($s_state)){

                           $courier->s_state = $s_state;

                       }

                       if(!empty($s_city)){

                           $courier->s_city = $s_city;
                       }

                       $courier->s_email = $s_email;
                       $courier->r_name = $r_name;
                       $courier->r_company = $r_comapny;
                       $courier->r_address1 = $r_address1;
                       $courier->r_address2 = $r_address2;
                       $courier->r_phone = $r_phone;
                       $courier->r_zip_code = $r_zip_code;
                       $courier->r_email = $r_email;
                       $courier->description = $description;

                       if(!empty($r_country)){
                           $country_data = Country::where('name',$r_country)->first();
                           if($country_data != null){
                               $courier->r_country = $country_data->id;
                           }
                       }

                       if(!empty($r_state)){
                               $courier->r_state = $r_state;
                       }

                       if(!empty($r_city)){
                         $courier->r_city = $r_city;
                       }

                       $courier->save();
                       $courier_payment = Courier_payment::where('courier_id',$courier_id)->first();
                       if($courier_payment != null){

                           if(!empty($total_amount)){
                               $courier_payment->total = $total_amount;
                           }

                           if(!empty($paid_amount)){
                               $courier_payment->pay_amount = $paid_amount;
                           }

                           if(!empty($remaining_amount)){
                               $courier_payment->remaining = $remaining_amount;
                           }
                           $courier_payment->payment_date = date('Y-m-d');

                           if(!empty($discount)){
                               $courier_payment->discount = $discount;
                           }

                           $courier_payment->save();

                       }else{
                           $courier_payment = new Courier_payment();
                           $courier_payment->courier_id = $courier_id;
                           $courier_payment->user_id = $courier->user_id;

                           if(!empty($total_amount)){
                               $courier_payment->total = $total_amount;
                           }

                           if(!empty($paid_amount)){
                               $courier_payment->pay_amount = $paid_amount;
                           }

                           if(!empty($remaining_amount)){
                               $courier_payment->remaining = $remaining_amount;
                           }

                           if(!empty($discount)){
                               $courier_payment->discount = $discount;
                           }
                           $courier_payment->payment_date = date('Y-m-d');

                           $courier_payment->save();

                       }


                           $shippment = Shippment::where('courier_id',$courier_id)->first();
                           if($shippment != null){

                               if(!empty($package_type)){
                                   $package_type_id = $package_types[$package_type];
                                   $shippment->package_type_id = $package_type_id;
                               }
                               if(!empty($service_type)){
                                   $service_type_id = $service_types[$service_type];
                                   $shippment->service_type_id = $service_type_id;
                               }

                               if(!empty($weight)){
                                   $shippment->weight = $weight;
                               }
                               if(!empty($carriage_value)){
                                   $shippment->carriage_value = $carriage_value;
                               }
                               $shippment->save();
                           }


                   }

                   /*else{
                       // Create new record
                       $courier= new Courier();
                       if(!empty($agent_name)){
                           $courier_user = User::where('name',$agent_name)->first();
                           $courier->user_id= $courier_user->id;
                       }
                       $courier->unique_name=$this->getCourierUniqueName(\Auth::user()->id);
                       $courier->barcode_no= rand();
                       $status_data = Status::where('name',$status)->first();
                       if($status_data !=null){
                           $courier->status_id = $status_data->id;
                       }
                       $courier->tracking_no = $tracking_no;

                       if(!empty($tracking_url) && $tracking_url != 'NA'){
                           $courier->tracking_url = $tracking_url;
                       }
                       $courier->s_name = $s_name;
                       $courier->s_company = $s_company;
                       $courier->s_address1 = $s_address1;
                       $courier->s_address2 = $s_address2;
                       $courier->s_phone = $s_phone;
                       if(!empty($s_country)){
                           $country_data = Country::where('name',$s_country)->first();
                           if($country_data != null){
                               $courier->s_country = $country_data->id;
                           }
                       }
                       if(!empty($s_state)){

                           $courier->s_state = $s_state;
                       }

                       if(!empty($s_city)){

                           $courier->s_city = $s_city;
                       }
                       $courier->s_email = $s_email;
                       $courier->r_name = $r_name;
                       $courier->r_company = $r_comapny;
                       $courier->r_address1 = $r_address1;
                       $courier->r_address2 = $r_address2;
                       $courier->r_phone = $r_phone;
                       $courier->r_zip_code = $r_zip_code;
                       $courier->r_email = $r_email;
                       $courier->description = $description;
                       if(!empty($r_country)){
                           $country_data = Country::where('name',$r_country)->first();
                           if($country_data != null){
                               $courier->r_country = $country_data->id;
                           }
                       }

                       if(!empty($r_state)){
                           $courier->r_state = $r_state;
                       }

                       if(!empty($r_city)){
                           $courier->r_city = $r_city;
                       }

                       $courier->save();

                       $courier_id = $courier->id;
                        // Save the data Courier Charge
                       $courier_charge = new Courier_charge();
                       $courier_charge->courier_id = $courier_id;
                       $courier_charge->user_id = $courier->user_id;
                       if(!empty($amount)){
                           $courier_charge->amount = $amount;
                       }

                       if(!empty($pickup_charge)){
                           $courier_charge->pickup_charge = $pickup_charge;
                       }

                       if(!empty($total)){
                           $courier_charge->total = $total;
                       }
                       if(!empty($shipped)){
                           $courier_service = Courier_service::where('name',$shipped)->first();
                           if($courier_service != null){
                               $courier_charge->courier_service_id = $courier_service->id;
                           }

                       }
                       $courier_charge->save();

                       // Save the data of Courier Shippment Details
                       $shippment = new Shippment();

                       $shippment->courier_id = $courier_id;

                       if(!empty($pickup_drop)) {
                           $shippment->courier_status = strtolower($pickup_drop);
                       }
                       if(!empty($package_type)){
                           $package_type_id = $package_types[$package_type];
                           $shippment->package_type_id = $package_type_id;
                       }
                       if(!empty($service_type)){
                           $service_type_id = $service_types[$service_type];
                           $shippment->service_type_id = $service_type_id;
                       }
                       if(!empty($content_type)){
                           $content_type_id = $content_types[$content_type];
                           $shippment->content_type_id = $content_type_id;
                       }
                       if(!empty($weight)){
                           $shippment->weight = $weight;
                       }
                       if(!empty($carriage_value)){
                           $shippment->carriage_value = $carriage_value;
                       }
                       $shippment->save();

                   }*/

               }

                $i++;
            }

        }

        \Session::flash('message', 'Courier has been importeded successfully!');
        return redirect('/'.\Auth::user()->user_type.'/couriers');
    }

    public function update_pickup_status(Request $request){

        $courierIds = $request->courierIds;
        $pickup_status = $request->pickup_status;
        foreach ($courierIds as $courierId){
            $shippment = Shippment::where('courier_id',$courierId)->first();
            if($shippment != null){
                $shippment->courier_status= strtolower($pickup_status);
                $shippment->save();
            }
        }
    }

    public function getCourierUniqueName($user_id){
        $user = User::find($user_id);
        $user_unique_name = $user->profile->unique_name;
        if(empty($user_unique_name)){
            $user_unique_name = strtoupper(substr($user->profile->first_name, 0, 2));
        }
        $user_couriers_count = Courier::where('user_id',$user_id)->count();
        $code = '1';
        if($user_couriers_count > 0){
          $code = $user_couriers_count+1;
        }
        $courier_unique_name = $user_unique_name."000".$code;
        return $courier_unique_name;

    }
    public function generateBarcode($id){

        $courier= Courier::find($id);
        $data['courier']=$courier;
        return view('couriers.barcode',$data);
    }

    public function boxDetails($id){

        $courier= Courier::find($id);
        if($courier != null){
            $data['courier']=$courier;
            $data['content_types']=Content_type::all();
            $data['content_unints']=Content_type::pluck('unit_type', 'id')->toArray();
            $data['no_of_boxes']=$courier->no_of_boxes;

            $courier_boxes = Courier_box::with('courier_box_items')->where('courier_id',$id)->orderBy('id','asc')->get();

            if($courier_boxes->count() > 0 ){

                $boxes =[];
                foreach ($courier_boxes as $cbk=> $cb){
                    $box_key = $cbk+1;
                    $courier_box_items = $cb->courier_box_items;
                    if($cb->courier_box_items->count() > 0){
                        foreach ($courier_box_items as $key=> $cbi){
                            $boxes[$box_key]['items'][$key] = ['item_name'=>$cbi->content_type_id,'item_unit'=>$cbi->unit_type,'qty'=>$cbi->qty];
                        }
                    }else{
                        $boxes[$box_key]['items'][0] = ['item_name'=>"",'item_unit'=>null,'qty'=>null];
                    }

                    $boxes[$box_key]['cb']=['breadth'=>$cb->breadth,'width'=>$cb->width,'height'=>$cb->height,'weight'=>$cb->weight];

                }

                $data['boxes']=$boxes;

            }else{
                $boxes =[];
                for($i=1;$i<=$courier->no_of_boxes;$i++){
                    $items['items'][0] = ['item_name'=>"",'item_unit'=>null,'qty'=>null];
                    $boxes[$i]=$items;
                    $boxes[$i]['cb']=['breadth'=>"",'width'=>"",'height'=>"",'weight'=>""];

                }
                $data['boxes']=$boxes;

            }


            $data['courier']=$courier;
            /*$courier_payment = Courier_payment::where('courier_id',$id)->first();
            if($courier_payment != null){
                if(empty($courier_payment->total_amount))
                {
                    $courier_payment->total_amount=0;
                }

                if(empty($courier_payment->pay_amount))
                {
                    $courier_payment->pay_amount=0;
                }

                if(empty($courier_payment->remaining))
                {
                    $courier_payment->remaining=0;
                }

                if(empty($courier_payment->discount))
                {
                    $courier_payment->discount=0;
                }

                $data['courier_payment']=$courier_payment;

            }else{
                $courier_payment = new Courier_payment();
                $courier_payment->total=null;
                $courier_payment->pay_amount=0;
                $courier_payment->remaining=0;
                $courier_payment->discount=0;
                $courier_payment->payment_date=date('Y-m-d');
                $data['courier_payment']=$courier_payment;

            }*/

            return view('couriers.box_details',$data);
        }else{
            abort(404);
        }

    }

    public function saveBoxDetails(Request $request){
        $input = $request->all();

        $courier_id =$input['courier_id'];
        $courier= Courier::find($courier_id);


       /* if(\Auth::user()->user_type != 'agent'){

            $validator = Validator::make($request->all(), [
                'payment_date' => 'required',
                'pay_amount' => 'required',
                'total' => 'required',

            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'payment_date' => 'required',
                'total' => 'required',
            ]);
        }


        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/couriers/box_details/'.$courier_id)
                ->withErrors($validator)
                ->withInput();
        }
        */

        if($courier != null){

            $courier_boxes = $input['box'];
            $shippment_weight = $courier->shippment->weight;
            $all_boxes_weight = $this->getAllBoxesWeight($courier_boxes);

            if($all_boxes_weight >= $shippment_weight){

                $check_courier_boxes = Courier_box::where('courier_id',$courier_id)->get();
                if($check_courier_boxes->count() >0){
                    Courier_box::where('courier_id',$courier_id)->delete();
                    Courier_box_item::where('courier_id',$courier_id)->delete();
                }

                foreach ($courier_boxes as $key=> $cb){
                    $breadth = $cb['breadth'];
                    $width = $cb['width'];
                    $height = $cb['height'];
                    $weight = $cb['weight'];
                    $box_items = $cb['items'];

                    $courier_box = new Courier_box();
                    $courier_box->courier_id = $courier_id;
                    $courier_box->box_name = "Box-".$key;
                    $courier_box->breadth = $breadth;
                    $courier_box->width = $width;
                    $courier_box->height = $height;
                    $courier_box->weight = $weight;
                    $courier_box->save();
                    $courier_box_id = $courier_box->id;

                    foreach ($box_items as $item){
                        $content_type_id = isset($item['content_type_id'])?$item['content_type_id']:"";
                        if(!empty($content_type_id)){
                            $unit_type = $item['unit_type'];
                            $qty = $item['qty'];
                            $courier_box_item = new Courier_box_item();
                            $courier_box_item->courier_id = $courier_id;
                            $courier_box_item->courier_box_id = $courier_box_id;
                            $courier_box_item->content_type_id = $content_type_id;
                            $courier_box_item->unit_type = $unit_type;
                            $courier_box_item->qty = $qty;
                            $courier_box_item->save();
                        }

                    }


                }


                /*$input['payment_date']=date('Y-m-d',strtotime($request->payment_date));
                $couier_payment = Courier_payment::where('courier_id',$courier_id)->first();
                if($couier_payment != null){
                    $couier_payment->id = $couier_payment->id;
                    $couier_payment->total= $input['total'];
                    if( \Auth::user()->user_type != 'agent') {
                        $couier_payment->pay_amount = $input['pay_amount'];
                        $couier_payment->remaining = $input['remaining'];
                        $couier_payment->discount = $input['discount'];
                    }
                    $couier_payment->payment_date= date('Y-m-d',strtotime($request->payment_date));
                    $couier_payment->save();
                }else{
                    Courier_payment::create($input);
                }*/


                if(isset($input['back'])){


                    return redirect('/'.\Auth::user()->user_type.'/couriers/'.$courier_id.'/edit');

                }else if(isset($input['save_box'])){
                    return redirect('/'.\Auth::user()->user_type.'/couriers/'.$courier_id);

                }

            }else{

                if(isset($input['back'])){

                    $check_courier_boxes = Courier_box::where('courier_id',$courier_id)->get();
                    if($check_courier_boxes->count() >0){
                        Courier_box::where('courier_id',$courier_id)->delete();
                        Courier_box_item::where('courier_id',$courier_id)->delete();
                    }

                    foreach ($courier_boxes as $key=> $cb){
                        $breadth = $cb['breadth'];
                        $width = $cb['width'];
                        $height = $cb['height'];
                        $weight = $cb['weight'];
                        $box_items = $cb['items'];

                        $courier_box = new Courier_box();
                        $courier_box->courier_id = $courier_id;
                        $courier_box->box_name = "Box-".$key;
                        $courier_box->breadth = $breadth;
                        $courier_box->width = $width;
                        $courier_box->height = $height;
                        $courier_box->weight = $weight;
                        $courier_box->save();
                        $courier_box_id = $courier_box->id;

                        foreach ($box_items as $item){
                            $content_type_id = isset($item['content_type_id'])?$item['content_type_id']:"";
                            if(!empty($content_type_id)){
                                $unit_type = $item['unit_type'];
                                $qty = $item['qty'];
                                $courier_box_item = new Courier_box_item();
                                $courier_box_item->courier_id = $courier_id;
                                $courier_box_item->courier_box_id = $courier_box_id;
                                $courier_box_item->content_type_id = $content_type_id;
                                $courier_box_item->unit_type = $unit_type;
                                $courier_box_item->qty = $qty;
                                $courier_box_item->save();
                            }

                        }


                    }


                    return redirect('/'.\Auth::user()->user_type.'/couriers/'.$courier_id.'/edit');

                }elseif(isset($input['save_box'])){
                    $request->session()->flash('error_message', 'Courier weight and all boxes weight does not equal!');
                    return redirect('/'.\Auth::user()->user_type.'/couriers/box_details/'.$courier_id);

                }

            }



        }else{
            abort(404);
        }



    }

    public function paymentDetails($id){


        $courier= Courier::find($id);
        if($courier != null){

            $courier_payment = Courier_payment::where('courier_id',$id)
                                                //->where('user_id',\Auth::user()->id)
                                                ->first();
            $data['courier']=$courier;
            if($courier_payment != null){
                $data['courier_payment']=$courier_payment;

            }else{
                $courier_payment = new Courier_payment();
                $courier_payment->total=null;
                $courier_payment->pay_amount=0;
                $courier_payment->remaining=0;
                $courier_payment->discount=0;
                $courier_payment->payment_date=date('Y-m-d');
                $data['courier_payment']=$courier_payment;

            }

            return view('couriers.payment_details',$data);
        }else{
            abort(404);
        }

    }

    public function savePaymentDetails(Request $request){
        $input = $request->all();

        $courier_id =$input['courier_id'];

        if(\Auth::user()->user_type != 'agent'){

            $validator = Validator::make($request->all(), [
                'payment_date' => 'required',
                'pay_amount' => 'required',
                'total' => 'required',

            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'payment_date' => 'required',
                'total' => 'required',
            ]);
        }


        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/couriers/payment_details/'.$courier_id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $input['payment_date']=date('Y-m-d',strtotime($request->payment_date));
        $couier_payment = Courier_payment::where('courier_id',$courier_id)->first();
        if($couier_payment != null){
            $couier_payment->id = $couier_payment->id;
            $couier_payment->total= $input['total'];
           if( \Auth::user()->user_type != 'agent'){
               $couier_payment->pay_amount= $input['pay_amount'];
               $couier_payment->remaining= $input['remaining'];
               $couier_payment->discount= $input['discount'];
           }

            $couier_payment->payment_date= date('Y-m-d',strtotime($request->payment_date));
            $couier_payment->save();
        }else{
            Courier_payment::create($input);
        }

        $request->session()->flash('message', 'Courier has been added successfully!');
        return redirect('/'.\Auth::user()->user_type.'/couriers');
    }

   public function getAllBoxesWeight($courier_boxes){
        $total_weight=0;

        foreach ($courier_boxes as $box){
            $total_weight+=$box['weight'];
        }
        return $total_weight;

    }

   public function courierReport($id){

       $courier= Courier::find($id);
       $t="courier_".time().".xlsx";
       if($courier != null){
           // $courier_unique_name = $courier->unique_name;
           return Excel::download(new CourierExport($courier), $t);

       }else {
           abort(404);
       }
    }

   public function getSenderPhone(Request $request){
        $input = $request->all();
        $search_key = $input['q'];

       $sender_phones =  Courier::where('s_phone','like',"%{$search_key}%")
                            ->groupBy('s_phone')
                            ->get();
        return response()->json($sender_phones);



    }

   public function getRecipientAddress(Request $request){

        $input = $request->all();
        $search_key = $input['q'];

        $recipient_details =  Courier::with('receiver_country')->where('s_phone','like',"%{$search_key}%")
                            ->groupBy('r_name')
                            ->get();
        return response()->json($recipient_details);
    }

    public function getSenderName(Request $request){
        $input = $request->all();
        $search_key = $input['q'];

        $sender_phones =  Courier::where('s_name','like',"%{$search_key}%")
            ->groupBy('s_name')
            ->get();
        return response()->json($sender_phones);



    }

}
