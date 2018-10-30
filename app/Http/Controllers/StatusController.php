<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['statuses']=Status::all();
        return view('admin.status.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.status.create');
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
            return redirect()->route('status.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $input['code_name'] = str_replace(" ","_",strtolower($input['name']));
        Status::create($input);
        $request->session()->flash('message', 'Status has been added successfully!');
        return redirect()->route('status.index');

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
        $data['status']=Status::find($id);
        return view('admin.status.edit',$data);
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
            return redirect()->route('status.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $status = Status::find($id);
        if($status != null){
            $status->name = $input['name'];
            $status->code_name = str_replace(" ","_",strtolower($input['name']));
            $status->save();
        }
        $request->session()->flash('message', 'Status has been updated successfully!');
        return redirect()->route('status.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Status::where('id',$id)->delete();
        \Session::flash('message', 'Status has been deleted successfully!');
        return redirect()->route('status.index');
    }
}
