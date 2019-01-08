<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;
use App\Models\Payment;
use App\Models\Expense;


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
            $where_p[] = ['payments.user_id', $user_id];
             $where_ex[] = ['expenses.user_id', $user_id];

        }

      
        if( $user_id > 0 && $from_date !="" && $end_date != ""){

            $payments= Payment::with('user')->OrderBy('updated_at','desc')
                                ->whereDate('payment_date','>=', $from_date)
                                ->whereDate('payment_date', '<=',$end_date)
                                ->where($where_p);

             $expenses= Expense::with(['expense_type','user'])->OrderBy('updated_at','desc')
                                ->whereDate('expense_date','>=', $from_date)
                                ->whereDate('expense_date', '<=',$end_date)
                                ->where($where_ex);

        }else{

            $payments= Payment::with('user')->OrderBy('updated_at','desc')
                                            ->whereDate('payment_date','>=', $from_date)
                                            ->whereDate('payment_date', '<=',$end_date);

            $expenses= Expense::with(['expense_type','user'])
                                ->OrderBy('updated_at','desc')
                                ->whereDate('expense_date','>=', $from_date)
                                ->whereDate('expense_date', '<=',$end_date);     

        }




        $payment_data = $payments->get();

        $expense_data = $expenses->get();


        $payment_grouped = $payment_data->groupBy('payment_date');

        $expense_grouped = $expense_data->groupBy('expense_date');


        $total_payment = $payments->sum('amount');
        $total_expense = $expenses->sum('amount');     
       // dd($expense_grouped->toArray());

        $payment_expense_arr = array_merge_recursive($payment_grouped->toArray(),$expense_grouped->toArray());

        $payments_expense_data=[];
        foreach ($payment_expense_arr as $key => $pe) {
            foreach ($pe as $key => $value) {
               $payments_expense_data[]=$value;
            }
           
        }
       // dd($payments_expense_data);
         $response_data['total_payment']=$total_payment;
         $response_data['total_expense']=$total_expense;
         $response_data['total']=$total_payment - $total_expense;
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
            return view('store.reports.agent_payment',$data);

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

}
