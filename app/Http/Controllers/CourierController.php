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
        if(\Auth::user()->user_type == 'agent' || \Auth::user()->user_type == 'store'){
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
        $data['courier_unique_no'] = $this->getCourierUniqueName(\Auth::user()->id);
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
        $input['unique_name']=$this->getCourierUniqueName(\Auth::user()->id);
        $input['barcode_no']= rand();
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
//        $data['s_states']=State::where('country_id',$courier->s_country)->get();
//        $data['s_cities']=City::where('state_id',$courier->s_state)->get();
//        $data['r_states']=State::where('country_id',$courier->r_country)->get();
//        $data['r_cities']=City::where('state_id',$courier->r_state)->get();
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

        $courier_joins = Courier::with(['agent','status','shippment','courier_charge','receiver_country']);
        if($type == 'all'){
            $where = [];
            if($user_type == 'agent' || $user_type == 'store'){
                $where[] = ['couriers.user_id', $user_id];
            }

            $couriers= $courier_joins
                    ->whereDate('updated_at','>=', date('Y-m-d'))
                    ->whereDate('updated_at', '<=',date('Y-m-d'))
                    ->where($where)
                    ->OrderBy('updated_at','desc');
        }else{
            $where = [];
            if($user_type == 'agent' || $user_type == 'store'){
                $where[] = ['couriers.user_id', $user_id];
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
            if ($agent_name !="" ) {

                $couriers= $courier_joins
                                    ->where($where)
                                    ->whereHas('agent',function ($query) use($agent_name){
                                        $query->where('name', 'like', "%{$agent_name}%");
                                    });
            }

            if($from_date !="" && $end_date != ""){

                $couriers= $courier_joins
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
        $writer->writeLine(['Courier Id','Unique Name','Agent Name', 'Status', 'Tracking No',
                            'Shipped','Pickup/Drop','Amount','Pickup Charge',
                             'Total','Sender Name',
                             'Sender Company', 'Sender Address1','Sender Address2',
                             'Sender Phone', 'Sender Country','Sender State',
                             'Sender City', 'Sender Email','Receiver Name',
                             'Receiver Company', 'Receiver Address1','Receiver Address2',
                             'Receiver Phone', 'Receiver Country','Receiver State',
                             'Receiver City','Receiver Zipcode',
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
            if($user_type == 'agent' || $user_type == 'store'){
                $where[] = ['couriers.user_id', $user_id];
            }
            $couriers= Courier::with(['agent','status','shippment','courier_charge'])
                                ->where('status_id',1)
                                ->where($where);
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
        $records = $couriers->paginate(15);

        foreach ($records as $courier){

            $s_country = ($courier->sender_country != null)?$courier->sender_country->name:"";
            $s_state = ($courier->s_state != null)?$courier->s_state:"";
            $s_city = ($courier->s_city != null)?$courier->s_city:"";
            $r_country = ($courier->receiver_country != null)?$courier->receiver_country->name:"";
            $r_state = ($courier->r_state != null)?$courier->r_state:"";
            $r_city = ($courier->r_city != null)?$courier->r_city:"";
            $shipped=null;
            $amount=0;
            $pickup_charge=0;
            $total=0;
            if($courier->courier_charge != null){

                if($courier->courier_charge->courier_service_id !=null){
                    $shipped = $courier->courier_charge->courier_service->name;
                }
                $amount = $courier->courier_charge->amount;
                $pickup_charge = $courier->courier_charge->pickup_charge;
                $total = $courier->courier_charge->total;

            }
            $pickup = ucfirst($courier->shippment->courier_status);
            $temp = [
                $courier->id,
                $courier->unique_name,
                $courier->agent->name,
                $courier->status->name,
                $courier->tracking_no,
                $shipped,
                $pickup,
                $amount,
                $pickup_charge,
                $total,
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

    public function importCourierCSv(Request $request){

        if($request->file('courier_csv'))
        {
            $path = $request->file('courier_csv')->getRealPath();
            $reader = \CsvReader::open($path);
            $i=0;
            while (($line = $reader->readLine()) !== false) {
               if($i >0 ){

                   $courier_id = $line[0];
                   $unique_name = $line[1];
                   $agent_name = $line[2];
                   $status = $line[3];
                   $tracking_no = $line[4];
                   $shipped = $line[5];
                   $pickup_drop = $line[6];
                   $amount = $line[7];

                   $pickup_charge = $line[8];
                   $total = $line[9];
                   $s_name = $line[10];
                   $s_company = $line[11];
                   $s_address1 = $line[12];
                   $s_address2 = $line[13];
                   $s_phone = $line[14];
                   $s_country = $line[15];
                   $s_state = $line[16];
                   $s_city = $line[17];
                   $s_email = $line[18];
                   $r_name = $line[19];
                   $r_comapny = $line[20];
                   $r_address1 = $line[21];
                   $r_address2 = $line[22];
                   $r_phone = $line[23];
                   $r_country = $line[24];
                   $r_state = $line[25];
                   $r_city = $line[26];
                   $r_zip_code = $line[27];
                   $r_email = $line[28];
                   $description = $line[29];

                   $courier = Courier::find($courier_id);

                   if($courier != null){
                        //update the record
                       $status_data = Status::where('name',$status)->first();
                       if($status_data !=null){
                           $courier->status_id = $status_data->id;
                       }
                       $courier->tracking_no = $tracking_no;


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
                       $courier_charge = Courier_charge::where('courier_id',$courier_id)->first();
                       if($courier_charge != null){

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

                       }else{
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

                       }

                       if(!empty($pickup_drop)){
                           $shippment = Shippment::where('courier_id',$courier_id)->first();
                           if($shippment != null){
                               $shippment->courier_status= strtolower($pickup_drop);
                               $shippment->save();
                           }

                       }

                   }
                   else{
                       // Create new record
                       $courier= new Courier();

                       $courier->unique_name=$this->getCourierUniqueName(\Auth::user()->id);
                       $courier->barcode_no= rand();
                       $status_data = Status::where('name',$status)->first();
                       if($status_data !=null){
                           $courier->status_id = $status_data->id;
                       }
                       $courier->tracking_no = $tracking_no;
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



                   }

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
}
