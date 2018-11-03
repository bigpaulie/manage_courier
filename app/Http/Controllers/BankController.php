<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['banks']=Bank::all();
        return view('admin.banks.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banks.create');
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
            return redirect()->route('banks.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Bank::create($input);
        $request->session()->flash('message', 'Bank has been added successfully!');
        return redirect()->route('banks.index');
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
        $data['bank']=Bank::find($id);
        return view('admin.banks.edit',$data);
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
            return redirect()->route('banks.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $bank = Bank::find($id);
        if($bank != null){
            $bank->name = $input['name'];
            $bank->save();
        }
        $request->session()->flash('message', 'Bank has been updated successfully!');
        return redirect()->route('banks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bank::where('id',$id)->delete();
        \Session::flash('message', 'Bank has been deleted successfully!');
        return redirect()->route('banks.index');
    }
}
