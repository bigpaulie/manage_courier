<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Courier;
use Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['payment_types']=['cheque'=>'Cheque','cash'=>'Cash','net_banking'=>'Net Banking'];
        $data['user_type']= \Auth::user()->user_type;
        $data['logged_user_id'] = \Auth::user()->id;

            return view('admin.payments.index',$data);

    }


    public function getPayments(Request $request){

        $input = $request->all();
        $user_id= isset($input['user_id'])?$input['user_id']:"";
        $from_date = isset($input['from_date'])?date('Y-m-d',strtotime($input['from_date'])):'';
        $end_date = isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):'';
        $type = isset($input['type'])?$input['type']:'';
        $user_type = $input['user_type'];

        $where=[];

        if($user_type == 'store'){
            $user_store_id = $input['user_store_id'];
            $where[] = ['payments.created_by', $user_store_id];
        }
        if($user_id > 0){
            $where[] = ['payments.user_id', $user_id];

        }

        if($type == 'all'){
           $payments= Payment::with('agent')
                                ->where($where)
                                ->OrderBy('created_at','desc');
        }

        else if( $user_id > 0 && $from_date !="" && $end_date != ""){

            $payments= Payment::with('agent')->OrderBy('created_at','desc')
                ->whereDate('payment_date','>=', $from_date)
                ->whereDate('payment_date', '<=',$end_date)
                ->where($where);

        }else{

            $payments= Payment::with('agent')->OrderBy('created_at','desc')
                            ->whereDate('payment_date','>=', $from_date)
                            ->whereDate('payment_date', '<=',$end_date);

        }

        $payment_data = $payments->paginate(50);
        $response_data['payment_data']=$payment_data;

        return response()->json($response_data);


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['banks']=Bank::pluck('name', 'id')->toArray();
        $data['payment_types']=['cheque'=>'Cheque','cash'=>'Cash','net_banking'=>'Net Banking'];
        $data['user_type']= \Auth::user()->user_type;
        $data['logged_user_id'] = \Auth::user()->id;
        return view('admin.payments.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $fields_array = [

            'amount' => 'required',
            'payment_by' => 'required',
            'payment_date' => 'required',


        ];

        if($request->payment_user_type == 'agent_store'){

            $user_id_field = ['user_id'=>'required',
            ];

            $fields_array = $fields_array+$user_id_field;
        }

        if($request->payment_user_type == 'walking_customer'){

            $customer_phone_field = ['customer_phone'=>'required',
            ];

            $fields_array = $fields_array+$customer_phone_field;
        }

        if($request->payment_by == 'cash'){

            $cash_fields = ['receiver_cash_name'=>'required',
            ];

            $fields_array = $fields_array+$cash_fields;
        }

        if($request->payment_by == 'cheque'){

            $cheque_fields = [
                'cheque_no'=>'required',
                'cheque_date'=>'required',

            ];

            $fields_array = $fields_array+$cheque_fields;
        }

        if($request->payment_by == 'net_banking'){

            $net_banking_fields = ['transaction_id'=>'required',

            ];

            $fields_array = $fields_array+$net_banking_fields;
        }

        $validator = Validator::make($request->all(),$fields_array );

        if ($validator->fails()) {

              return redirect('/'.\Auth::user()->user_type.'/payments/create')
                ->withErrors($validator)
                ->withInput();
        }


        $input = $request->all();

        $payment = new Payment();
        $payment->payment_user_type = $input['payment_user_type'];

        if($payment->payment_user_type == 'agent_store'){
            $payment->user_id = $input['user_id'];
        }
        if($payment->payment_user_type == 'walking_customer'){

            $payment->customer_phone = $input['customer_phone'];
            $payment->discount = $input['discount'];
            $walking_customer = Courier::where('s_phone',$payment->customer_phone)->first();
            $payment->walking_customer_name = $walking_customer->s_name." - ".$walking_customer->s_city;
        }


        $payment->bank_id = $input['bank_id'];
        $payment->payment_date = date('Y-m-d',strtotime($input['payment_date']));
        $payment->payment_by = $input['payment_by'];
        $payment->amount = $input['amount'];

        $payment->remark = $input['remark'];
        $payment->created_by = \Auth::user()->id;
        $payment->created_user_type = \Auth::user()->user_type;
        if($request->payment_by == 'cash'){
            $payment->reciver_name = $input['receiver_cash_name'];
        }

        if($request->payment_by == 'cheque'){
            $payment->cheque_bank_name = $input['cheque_bank_name'];
            $payment->reference_no = $input['reference_no'];
            $payment->cheque_no = $input['cheque_no'];
            $payment->cheque_date = date('Y-m-d',strtotime($input['cheque_date']));
        }

        if($request->payment_by == 'net_banking'){
            $payment->reciver_name = $input['net_banking_name'];
            $payment->transaction_id = $input['transaction_id'];
        }
        $payment->save();

        $request->session()->flash('message', 'Payment has been added successfully!');

        return redirect('/'.\Auth::user()->user_type.'/payments');
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
        $payment= Payment::find($id);

        $data['banks']=Bank::pluck('name', 'id')->toArray();
        $data['payment_types']=['cheque'=>'Cheque','cash'=>'Cash','net_banking'=>'Net Banking'];
        $data['payment']=$payment;
        $data['user_type']= \Auth::user()->user_type;
        $data['logged_user_id'] = \Auth::user()->id;

        return view('admin.payments.edit',$data);
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
        $fields_array = [

            'amount' => 'required',
            'payment_by' => 'required',
            'payment_date' => 'required',


        ];


        if($request->payment_user_type == 'agent_store'){

            $user_id_field = ['user_id'=>'required',
            ];

            $fields_array = $fields_array+$user_id_field;
        }

        if($request->payment_user_type == 'walking_customer'){

            $customer_phone_field = ['customer_phone'=>'required',
            ];

            $fields_array = $fields_array+$customer_phone_field;
        }


        if($request->payment_by == 'cash'){

            $cash_fields = ['receiver_cash_name'=>'required',
            ];

            $fields_array = $fields_array+$cash_fields;
        }

        if($request->payment_by == 'cheque'){

            $cheque_fields = [
                'cheque_no'=>'required',
                'cheque_date'=>'required',

            ];

            $fields_array = $fields_array+$cheque_fields;
        }

        if($request->payment_by == 'net_banking'){

            $net_banking_fields = ['transaction_id'=>'required',

            ];

            $fields_array = $fields_array+$net_banking_fields;
        }

        $validator = Validator::make($request->all(),$fields_array );

        if ($validator->fails()) {

            return redirect('/'.\Auth::user()->user_type.'/payments/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }


        $input = $request->all();
        $payment = Payment::find($id);

        $payment->payment_user_type = $input['payment_user_type'];

        if($payment->payment_user_type == 'agent_store'){
            $payment->user_id = $input['user_id'];
        }
        if($payment->payment_user_type == 'walking_customer'){

            $payment->customer_phone = $input['customer_phone'];
            $payment->discount = $input['discount'];
            $walking_customer = Courier::where('s_phone',$payment->customer_phone)->first();
            $payment->walking_customer_name = $walking_customer->s_name." - ".$walking_customer->s_city;
        }



        $payment->bank_id = $input['bank_id'];
        $payment->payment_date = date('Y-m-d',strtotime($input['payment_date']));
        $payment->payment_by = $input['payment_by'];
        $payment->amount = $input['amount'];

        $payment->remark = $input['remark'];
        $payment->created_by = \Auth::user()->id;
        $payment->created_user_type = \Auth::user()->user_type;
        if($request->payment_by == 'cash'){
            $payment->reciver_name = $input['receiver_cash_name'];
        }

        if($request->payment_by == 'cheque'){
            $payment->cheque_bank_name = $input['cheque_bank_name'];
            $payment->reference_no = $input['reference_no'];
            $payment->cheque_no = $input['cheque_no'];
            $payment->cheque_date = date('Y-m-d',strtotime($input['cheque_date']));
        }

        if($request->payment_by == 'net_banking'){
            $payment->reciver_name = $input['net_banking_name'];
            $payment->transaction_id = $input['transaction_id'];
        }
        $payment->save();

        $request->session()->flash('message', 'Payment has been updated successfully!');

        return redirect('/'.\Auth::user()->user_type.'/payments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Payment::where('id',$id)->delete();
        \Session::flash('message', 'Payment has been deleted successfully!');
        return redirect('/'.\Auth::user()->user_type.'/payments');
    }
}
