<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package_type;
use Validator;

class PackagetypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['package_types']=Package_type::orderBy('created_at', 'desc')->get();
        return view('admin.package_types.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package_types.create');
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
            return redirect()->route('package_types.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Package_type::create($input);
        $request->session()->flash('message', 'Package Type has been added successfully!');
        return redirect()->route('package_types.index');
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
        $data['package_type']=Package_type::find($id);
        return view('admin.package_types.edit',$data);
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
            return redirect()->route('package_types.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $package_type = Package_type::find($id);
        if($package_type != null){
            $package_type->name = $input['name'];
            $package_type->save();
        }
        $request->session()->flash('message', 'Package Type has been updated successfully!');
        return redirect()->route('package_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Package_type::where('id',$id)->delete();
        \Session::flash('message', 'Package Type has been deleted successfully!');
        return redirect()->route('package_types.index');
    }
}
