<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content_type;
use Validator;

class ContenttypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['content_types']=Content_type::orderBy('created_at', 'desc')->get();
        return view('admin.content_types.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.content_types.create');
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
            'unit_type' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('content_types.create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        Content_type::create($input);
        $request->session()->flash('message', 'Content Type has been added successfully!');
        return redirect()->route('content_types.index');
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
        $data['content_type']=Content_type::find($id);
        return view('admin.content_types.edit',$data);
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
            'unit_type' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->route('content_types.edit',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $content_type = Content_type::find($id);
        if($content_type != null){
            $content_type->name = $input['name'];
            $content_type->unit_type = $input['unit_type'];
            $content_type->save();
        }
        $request->session()->flash('message', 'Content Type has been updated successfully!');
        return redirect()->route('content_types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Content_type::where('id',$id)->delete();
        \Session::flash('message', 'Content Type has been deleted successfully!');
        return redirect()->route('content_types.index');
    }
}
