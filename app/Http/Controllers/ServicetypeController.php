<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service_type;
use Validator;

class ServicetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['service_types']=Service_type::all();
        return view('admin.service_types.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.service_types.create');
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
            return redirect()->route('service_types.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Service_type::create($input);
        $request->session()->flash('message', 'Service Type has been added successfully!');
        return redirect()->route('service_types.index');

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
        $data['service_type']=Service_type::find($id);
        return view('admin.service_types.edit',$data);
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
            return redirect()->route('service_types.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $service_type = Service_type::find($id);
        if($service_type != null){
            $service_type->name = $input['name'];
            $service_type->save();
        }
        $request->session()->flash('message', 'Service Type has been updated successfully!');
        return redirect()->route('service_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service_type::where('id',$id)->delete();
        \Session::flash('message', 'Service Type has been deleted successfully!');
        return redirect()->route('service_types.index');
    }
}
