<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Country;

use Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['companies']=Company::orderBy('created_at', 'desc')->get();
        return view('admin.companies.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        $data['countries']=$countries;
        return view('admin.companies.create',$data);
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
            'country_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('companies.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $countries = Country::where('id',$input['country_id'])->first();
        $input['address']=$countries->name;
        Company::create($input);
        $request->session()->flash('message', 'Company has been added successfully!');
        return redirect()->route('companies.index');
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
        $data['company']=Company::find($id);
        $countries = Country::get();
        $data['countries']=$countries;
        return view('admin.companies.edit',$data);
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
            'country_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('companies.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $compnay = Company::find($id);
        if($compnay != null){
            $compnay->name = $input['name'];
            $compnay->country_id = $input['country_id'];
            $countries = Country::where('id',$input['country_id'])->first();
            $compnay->address=$countries->name;
            $compnay->save();
        }
        $request->session()->flash('message', 'Company has been updated successfully!');
        return redirect()->route('companies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::where('id',$id)->delete();
        \Session::flash('message', 'Company has been deleted successfully!');
        return redirect()->route('companies.index');
    }
}
