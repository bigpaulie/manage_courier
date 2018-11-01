<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense_type;
use App\Models\Expense;
use Validator;

class ExpenseController extends Controller
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
            $expenses= Expense::OrderBy('created_at','desc')
                                ->paginate(10);
        }else if($user_type == 'store'){
            $expenses= Expense::where('user_id',\Auth::user()->id)
                                ->OrderBy('created_at','desc')
                                ->paginate(10);
        }

        $data['expenses']=$expenses;
        return view('expenses.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['expense_types']=Expense_type::pluck('name', 'id')->toArray();
        $data['payment_types']=['cheque'=>'Cheque','cash'=>'Cash','net_banking'=>'Net Banking'];
        return view('expenses.create',$data);
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
            'expense_type_id' => 'required',
            'party_name' => 'required',
            'payment_by' => 'required',
            'expense_date' => 'required',


        ];
        if($request->payment_by == 'cash'){

            $cash_fields = ['receiver_cash_name'=>'required',
                            'cash_amount'=>'required',
                           ];

            $fields_array = $fields_array+$cash_fields;
        }

        if($request->payment_by == 'cheque'){

            $cheque_fields = ['cheque_no'=>'required',
                              'cheque_amount'=>'required',
                              'cheque_date'=>'required',
                              'cheque_name'=>'required',
            ];

            $fields_array = $fields_array+$cheque_fields;
        }

        if($request->payment_by == 'net_banking'){

            $net_banking_fields = ['transaction_id'=>'required',
                'net_banking_amount'=>'required',
                'net_banking_name'=>'required',

            ];

            $fields_array = $fields_array+$net_banking_fields;
        }

        $validator = Validator::make($request->all(),$fields_array );

        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/expenses/create')
                ->withErrors($validator)
                ->withInput();
        }


        $input = $request->all();
        $expense = new Expense();
        $expense->user_id = \Auth::user()->id;
        $expense->expense_type_id = $input['expense_type_id'];
        $expense->expense_date = date('Y-m-d',strtotime($input['expense_date']));
        $expense->party_name = $input['party_name'];
        $expense->debited_from = $input['debited_from'];
        $expense->description = $input['description'];
        if($request->payment_by == 'cash'){
            $expense->payment_by = 'Cash';
            $expense->amount = $input['cash_amount'];
            $expense->receiver_name = $input['receiver_cash_name'];
        }

        if($request->payment_by == 'cheque'){
            $expense->payment_by = 'Cheque';
            $expense->amount = $input['cheque_amount'];
            $expense->receiver_name = $input['cheque_name'];
            $expense->cheque_no = $input['cheque_no'];
            $expense->cheque_date = date('Y-m-d',strtotime($input['cheque_date']));
        }

        if($request->payment_by == 'net_banking'){
            $expense->payment_by = 'Net Banking';
            $expense->amount = $input['net_banking_amount'];
            $expense->receiver_name = $input['net_banking_name'];
            $expense->transaction_id = $input['transaction_id'];
        }
        $expense->save();

        $request->session()->flash('message', 'Expense has been added successfully!');

        return redirect('/'.\Auth::user()->user_type.'/expenses');
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
        $expense= Expense::find($id);


        $data['expense_types']=Expense_type::pluck('name', 'id')->toArray();
        $data['payment_types']=['cheque'=>'Cheque','cash'=>'Cash','net_banking'=>'Net Banking'];

        $key = array_search ($expense->payment_by, $data['payment_types']);
        $expense->payment_by = $key;
        $data['expense']=$expense;

        return view('expenses.edit',$data);
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
            'expense_type_id' => 'required',
            'party_name' => 'required',
            'payment_by' => 'required',
            'expense_date' => 'required',


        ];
        if($request->payment_by == 'cash'){

            $cash_fields = ['receiver_cash_name'=>'required',
                'cash_amount'=>'required',
            ];

            $fields_array = $fields_array+$cash_fields;
        }

        if($request->payment_by == 'cheque'){

            $cheque_fields = ['cheque_no'=>'required',
                'cheque_amount'=>'required',
                'cheque_date'=>'required',
                'cheque_name'=>'required',
            ];

            $fields_array = $fields_array+$cheque_fields;
        }

        if($request->payment_by == 'net_banking'){

            $net_banking_fields = ['transaction_id'=>'required',
                'net_banking_amount'=>'required',
                'net_banking_name'=>'required',

            ];

            $fields_array = $fields_array+$net_banking_fields;
        }

        $validator = Validator::make($request->all(),$fields_array );

        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/expenses/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }




        $input = $request->all();
        $expense = Expense::find($id);

        $expense->expense_type_id = $input['expense_type_id'];
        $expense->expense_date = date('Y-m-d',strtotime($input['expense_date']));
        $expense->party_name = $input['party_name'];
        $expense->debited_from = $input['debited_from'];
        $expense->description = $input['description'];
        if($request->payment_by == 'cash'){
            $expense->payment_by = 'Cash';
            $expense->amount = $input['cash_amount'];
            $expense->receiver_name = $input['receiver_cash_name'];
        }

        if($request->payment_by == 'cheque'){
            $expense->payment_by = 'Cheque';
            $expense->amount = $input['cheque_amount'];
            $expense->receiver_name = $input['cheque_name'];
            $expense->cheque_no = $input['cheque_no'];
            $expense->cheque_date = date('Y-m-d',strtotime($input['cheque_date']));
        }

        if($request->payment_by == 'net_banking'){
            $expense->payment_by = 'Net Banking';
            $expense->amount = $input['net_banking_amount'];
            $expense->receiver_name = $input['net_banking_name'];
            $expense->transaction_id = $input['transaction_id'];
        }
        $expense->save();

        $request->session()->flash('message', 'Expense has been updated successfully!');
        return redirect('/'.\Auth::user()->user_type.'/expenses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expense::where('id',$id)->delete();
        \Session::flash('message', 'Expense has been deleted successfully!');
        return redirect('/'.\Auth::user()->user_type.'/expenses');
    }
}
