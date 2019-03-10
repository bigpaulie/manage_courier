<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense_type;
use Validator;



class ExpensetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['expense_types']=Expense_type::orderBy('created_at', 'desc')->get();
        return view('admin.expense_types.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.expense_types.create');
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
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('expense_types.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Expense_type::create($input);
        $request->session()->flash('message', 'Expense Type has been added successfully!');
        return redirect()->route('expense_types.index');

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
        $data['expense_type']=Expense_type::find($id);
        return view('admin.expense_types.edit',$data);
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
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('expense_types.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $expense_type = Expense_type::find($id);
        if($expense_type != null){
            $expense_type->name = $input['name'];
            $expense_type->save();
        }
        $request->session()->flash('message', 'Expense Type has been updated successfully!');
        return redirect()->route('expense_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expense_type::where('id',$id)->delete();
        \Session::flash('message', 'Expense Type has been deleted successfully!');
        return redirect()->route('expense_types.index');
    }
}
