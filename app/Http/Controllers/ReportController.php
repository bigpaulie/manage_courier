<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\User_profile;
use App\Models\Courier_payment;



class ReportController extends Controller
{
    public function index(){
        $data=[];
        $user_type = \Auth::user()->user_type;
        if($user_type == 'admin'){
            return view('admin.reports.index',$data);

        }else if($user_type == 'store'){
            $data['user_id']=\Auth::user()->id;
            return view('store.reports.index',$data);

        }
    }

    public function generateReport(Request $request){

        $input = $request->all();
        $user_id= $input['user_id'];

        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        if($user_id > 0){
            $where[] = ['couriers.user_id', $user_id];

        }


       $courier_joins = Courier::with(['agent','status','shippment','courier_charge','receiver_country']);


        if( $user_id > 0 && $from_date !="" && $end_date != ""){

            $couriers= $courier_joins
                ->whereDate('updated_at','>=', $from_date)
                ->whereDate('updated_at', '<=',$end_date)
                ->where($where)
                ->OrderBy('updated_at','desc');
        }else{

            $couriers= $courier_joins
                ->whereDate('updated_at','>=', $from_date)
                ->whereDate('updated_at', '<=',$end_date)
                ->OrderBy('updated_at','desc');
        }

        $courier_data = $couriers->paginate(50);

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


    }


    public function generatePaymentExpense(Request $request){


        $input = $request->all();
        $user_id= isset($input['user_id'])?$input['user_id']:"";
        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
       
        if($user_id > 0){
            $where_p[] = ['payments.created_by', $user_id];
             $where_ex[] = ['expenses.user_id', $user_id];

        }

      
        if( $user_id > 0 && $from_date !="" && $end_date != ""){

            $payments= Payment::with('user')->OrderBy('updated_at','desc')
                                ->whereDate('payment_date','>=', $from_date)
                                ->whereDate('payment_date', '<=',$end_date)
                                ->where($where_p)
                                ->where('payment_user_type','agent_store');

            $walking_payments= Payment::with('user')->OrderBy('updated_at','desc')
                                ->whereDate('payment_date','>=', $from_date)
                                ->whereDate('payment_date', '<=',$end_date)
                                ->where($where_p)
                                ->where('payment_user_type','walking_customer');

            $courier_Ids = Courier::where('user_id',$user_id)->pluck('id')->toArray();

            $courier_payments = Courier_payment::where('user_id',$user_id)
                                            ->whereIn('courier_id',$courier_Ids)
                                             ->whereDate('payment_date','>=', $from_date)
                                             ->whereDate('payment_date', '<=',$end_date)
                                            ->orderBy('payment_date','desc');



            $expenses= Expense::with(['expense_type','user'])->OrderBy('updated_at','desc')
                                ->whereDate('expense_date','>=', $from_date)
                                ->whereDate('expense_date', '<=',$end_date)
                                ->where($where_ex);

        }else{

            $payments= Payment::with('user')->OrderBy('updated_at','desc')
                                            ->whereDate('payment_date','>=', $from_date)
                                            ->whereDate('payment_date', '<=',$end_date)
                                            ->where('payment_user_type','agent_store');


            $walking_payments= Payment::with('user')->OrderBy('updated_at','desc')
                                        ->whereDate('payment_date','>=', $from_date)
                                        ->whereDate('payment_date', '<=',$end_date)
                                        ->where('payment_user_type','walking_customer');



            $courier_payments = Courier_payment::whereDate('payment_date','>=', $from_date)
                                                ->whereDate('payment_date', '<=',$end_date)
                                                ->whereNotNull('pay_amount')
                                                ->orderBy('payment_date','desc');


            $expenses= Expense::with(['expense_type','user'])
                                ->OrderBy('updated_at','desc')
                                ->whereDate('expense_date','>=', $from_date)
                                ->whereDate('expense_date', '<=',$end_date);     

        }




        $payment_data = $payments->get();
        $walking_payment_data = $walking_payments->get();
        $courier_payment_data = $courier_payments->get();


        $expense_data = $expenses->get();


        $payment_grouped = $payment_data->groupBy('payment_date');
        $walking_payment_grouped = $walking_payment_data->groupBy('payment_date');
        $courier_payment_grouped = $courier_payment_data->groupBy('payment_date');

        $expense_grouped = $expense_data->groupBy('expense_date');


        $total_payment = $payments->sum('amount');

        $total_expense = $expenses->sum('amount');

        $total_courier_payment = $courier_payments->sum('pay_amount');

        $total_walking_payments = $walking_payments->sum('amount');
        //echo $total_walking_payments;exit;

        $all_total = $total_payment+$total_courier_payment+$total_walking_payments;
       // dd($expense_grouped->toArray());

        $payment_expense_arr = array_merge_recursive($payment_grouped->toArray(),
                                                     $walking_payment_grouped->toArray(),
                                                     $courier_payment_grouped->toArray(),
                                                     $expense_grouped->toArray()
                                                    );

        $payments_expense_data=[];
        foreach ($payment_expense_arr as $key => $pe) {
            foreach ($pe as $key => $value) {
               $payments_expense_data[]=$value;
            }
           
        }
       // dd($payments_expense_data);
         $response_data['total_payment']=$all_total;
         $response_data['total_expense']=$total_expense;
         $response_data['total']=$all_total - $total_expense;
        $response_data['payments_expense_data']=$payments_expense_data;

        return response()->json($response_data);

    }

    public function walkingCustomer(){

        $data=[];
        $user_type = \Auth::user()->user_type;
        if($user_type == 'admin'){
            return view('admin.reports.walking_customer',$data);

        }else if($user_type == 'store'){
            $data['user_id']=\Auth::user()->id;
            $data['user_type']=\Auth::user()->user_type;
            return view('store.reports.walking_customer',$data);

        }
    }

    public function agentPayment(){

        $data=[];
        $user_type = \Auth::user()->user_type;
        if($user_type == 'admin'){
            return view('admin.reports.agent_payment',$data);

        }else if($user_type == 'store'){
            $data['user_id']=\Auth::user()->id;
            $data['user_type']=\Auth::user()->user_type;
            return view('store.reports.agent_payment',$data);

        }else if($user_type == 'agent'){
            $data['user_id']=\Auth::user()->id;
            $data['user_type']=\Auth::user()->user_type;
            return view('agent.reports.agent_payment',$data);
        }
    }

    public function paymentExpense(){
        $data=[];
        $user_type = \Auth::user()->user_type;
        if($user_type == 'admin'){
            return view('admin.reports.payment_expense',$data);

        }else if($user_type == 'store'){
            $data['user_id']=\Auth::user()->id;
            return view('store.reports.payment_expense',$data);

        }
    }

    public function getAgentPayment(Request $request){

    $input = $request->all();
    $logged_user_id= isset($input['logged_user_id'])?$input['logged_user_id']:"";
    $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
    $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
    $agent_id = isset($input['agent_id'])?$input['agent_id']:'';
    if(!empty($agent_id) && $agent_id > 0){
        $agent_ids = [$agent_id];

    }else{
        $agent_ids = User_profile::where('store_id',$logged_user_id)->pluck('user_id')->toArray();
    }

    $courier_payments = Courier_payment::with('agent')
        ->whereIn('user_id',$agent_ids)
        ->whereDate('payment_date','>=', $from_date)
        ->whereDate('payment_date', '<=',$end_date)
        ->orderBy('payment_date','desc')
        ->get();
    $agent_payments =   Payment::with('agent')
        ->whereIn('user_id',$agent_ids)
        ->whereDate('payment_date','>=', $from_date)
        ->whereDate('payment_date', '<=',$end_date)
        ->orderBy('payment_date','desc')
        ->get();


    $total_amount = $courier_payments->sum('total');
    $total_paid_amount = $agent_payments->sum('amount');

    $cp_grouped = $courier_payments->groupBy('payment_date');

    $ap_grouped = $agent_payments->groupBy('payment_date');


    $agent_payment_arr = array_merge_recursive($cp_grouped->toArray(),$ap_grouped->toArray());

    $agent_payment_data=[];
    foreach ($agent_payment_arr as $key => $pe) {
        foreach ($pe as $key => $value) {
            $agent_payment_data[]=$value;
        }

    }

    $response_data['agent_payment_data']=$agent_payment_data;

    $response_data['total_amount']=$total_amount;
    $response_data['total_paid_amount']=$total_paid_amount;

    return response()->json($response_data);



}

    public function getWalkingCustomerPayment(Request $request){

        $input = $request->all();
        $logged_user_id= isset($input['logged_user_id'])?$input['logged_user_id']:"";
       // $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        //$end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        $customer_phone = isset($input['customer_phone'])?$input['customer_phone']:'';
        if(!empty($customer_phone)){
            $courier_Ids = Courier::where('s_phone',$customer_phone)->pluck('id')->toArray();

        }else{
            $courier_Ids = Courier::where('user_id',$logged_user_id)->pluck('id')->toArray();
        }

        $courier_payments = Courier_payment::with('courier')
                                            ->where('user_id',$logged_user_id)
                                            ->whereIn('courier_id',$courier_Ids)
                                           // ->whereDate('payment_date','>=', $from_date)
                                           // ->whereDate('payment_date', '<=',$end_date)
                                            ->orderBy('payment_date','desc')
                                            ->get();


        $walking_payments =   Payment::with('courier')->where('created_by',$logged_user_id)
                                        ->where('payment_user_type','walking_customer')
                                        ->where('customer_phone',$customer_phone)
                                       // ->whereDate('payment_date','>=', $from_date)
                                        //->whereDate('payment_date', '<=',$end_date)
                                        ->orderBy('payment_date','desc')
                                        ->get();




        $total_courier_amount = $courier_payments->sum('total');
        $total_courier_paid_amount = $courier_payments->sum('pay_amount');
        $total_courier_discount = $courier_payments->sum('discount');

        $total_walking_payment = $walking_payments->sum('amount');
        $total_walking_discount = $walking_payments->sum('discount');

        $cp_grouped = $courier_payments->groupBy('payment_date');

        $wp_grouped = $walking_payments->groupBy('payment_date');


        $walking_payment_arr = array_merge_recursive($cp_grouped->toArray(),$wp_grouped->toArray());

        $walking_payment_data=[];
        foreach ($walking_payment_arr as $key => $pe) {
            foreach ($pe as $key => $value) {
                $walking_payment_data[]=$value;
            }

        }

        $response_data['walking_payment_data']=$walking_payment_data;

        $response_data['total_amount']=$total_courier_amount;
        $response_data['total_paid_amount']=$total_courier_paid_amount+$total_walking_payment;
        $response_data['total_discount']=$total_courier_discount+$total_walking_discount;

        return response()->json($response_data);



    }

}
