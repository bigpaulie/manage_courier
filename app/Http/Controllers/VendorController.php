<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['vendors']=Vendor::all();
        return view('admin.vendors.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vendors.create');
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
            return redirect()->route('vendors.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Vendor::create($input);
        $request->session()->flash('message', 'Vendor has been added successfully!');
        return redirect()->route('vendors.index');
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
        $data['vendor']=Vendor::find($id);
        return view('admin.vendors.edit',$data);
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
            return redirect()->route('vendors.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $vendor = Vendor::find($id);
        if($vendor != null){
            $vendor->name = $input['name'];
            $vendor->save();
        }
        $request->session()->flash('message', 'Vendor has been updated successfully!');
        return redirect()->route('vendors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Vendor::where('id',$id)->delete();
        \Session::flash('message', 'Vendor has been deleted successfully!');
        return redirect()->route('vendors.index');
    }

    public function getVendors(Request $request){
        $input = $request->all();
        $search_key = $input['searchTerm'];
        $vendors =  Vendor::where('name','like',"%{$search_key}%")
                               ->get();

        $user_data=[];
        foreach ($vendors as $vendor){
            $temp=[];
            $temp['id']= $vendor->id;
            $temp['text']= $vendor->name;
            $user_data[]=$temp;
        }
        return $user_data;


    }
}
