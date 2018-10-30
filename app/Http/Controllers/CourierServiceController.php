<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier_service;
use Validator;

class CourierServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['courier_services']=Courier_service::all();
        return view('admin.courier_services.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.courier_services.create');
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
            return redirect()->route('courier_services.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Courier_service::create($input);
        $request->session()->flash('message', 'Courier Service has been added successfully!');
        return redirect()->route('courier_services.index');
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
        $data['courier_service']=Courier_service::find($id);
        return view('admin.courier_services.edit',$data);
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
            return redirect()->route('courier_services.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $courier_service = Courier_service::find($id);
        if($courier_service != null){
            $courier_service->name = $input['name'];
            $courier_service->save();
        }
        $request->session()->flash('message', 'Courier Service has been updated successfully!');
        return redirect()->route('courier_services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Courier_service::where('id',$id)->delete();
        \Session::flash('message', 'Courier Service has been deleted successfully!');
        return redirect()->route('courier_services.index');
    }
}
