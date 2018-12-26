<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;
use App\Models\Status;
use App\Models\Vendor;
use App\Models\Manifest;
use App\Models\Manifest_item;
use App\Models\User;
use Session;
use Validator;


class ManifestController extends Controller
{

    public function index(){

        if(\Auth::user()->user_type == 'admin'){

            $manifests=Manifest::get();
        }
        if(\Auth::user()->user_type == 'store'){

            $logged_user_id = \Auth::user()->id;
            $manifests=Manifest::where('created_by',$logged_user_id)->get();
        }


        $data['manifests'] =$manifests;
        Session::forget('manifest_data');
        return view('admin.manifest.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_id = Status::where('code_name',"pending")->first()->id;

        $user_type = \Auth::user()->user_type;
        $courier_joins = Courier::with(['agent','status','receiver_country']);
        if($user_type == 'admin'){
            $couriers= $courier_joins
                ->where('status_id',$status_id)
                ->OrderBy('updated_at','desc')->get();
        }else if($user_type == 'store'){

            $agents_id= User::where('user_type','agent')
                ->whereHas('profile', function ($query) {
                    $query->where('store_id',\Auth::user()->id);
                })->pluck('id')->toArray();


            $agents_id = array_prepend($agents_id, \Auth::user()->id);

            $couriers= $courier_joins
                ->where('status_id',$status_id)
                ->whereIn('user_id',$agents_id)
                ->OrderBy('updated_at','desc')->get();
        }




        $data['vendors']=Vendor::pluck('name','id')->toArray();
        $data['couriers'] =$couriers;
       // dd(Session::get('manifest_data'));
        return view('admin.manifest.create',$data);

    }

    public function createManifest(Request $request){


        $input= $request->all();
        $courier_ids = $input['courier_id'];
        $manifest_data=[];

        $session_exists=0;
        if(Session::has('manifest_data')){
            $session_manifest_data = $request->session()->get('manifest_data');
            $session_courier_ids =$session_manifest_data['courier_ids'];
            $courier_ids= array_merge($courier_ids,$session_courier_ids);
            $session_exists=1;

            if(isset($input['bulk'])){
                $manifest_data['items']=isset($session_manifest_data['items'])?$session_manifest_data['items']:[];
            }

            if(isset($input['item'])){
                $manifest_data['bulk_items']=isset($session_manifest_data['bulk_items'])?$session_manifest_data['bulk_items']:[];
            }

        }
        $manifest_data['courier_ids']=$courier_ids;

        if(isset($input['item'])){
            if($session_exists && isset($session_manifest_data['items'])){
                $session_items =$session_manifest_data['items'];
                $manifest_data['items']=array_merge($session_items,$input['courier_id']);

            }else{
                $manifest_data['items']=$input['courier_id'];
            }

        }

        if(isset($input['bulk'])){

            if($session_exists && isset($session_manifest_data['bulk_items'])){
                $session_bulk_items =$session_manifest_data['bulk_items'];
                $count_bulk_items = count($session_bulk_items);
                $new_bulk_item[$count_bulk_items] = $input['courier_id'];

                $manifest_data['bulk_items']=array_merge($session_bulk_items,$new_bulk_item);

            }else{
                $manifest_data['bulk_items'][]=$input['courier_id'];
            }
        }



        $request->session()->put('manifest_data', $manifest_data);

       // return redirect()->route('manifest.create');

        return redirect('/'.\Auth::user()->user_type.'/manifest/create');


    }

    public function saveManifest(Request $request){


        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/manifest/create')
                ->withErrors($validator)
                ->withInput();
        }
        if($request->session()->has('manifest_data')){
            $input = $request->all();
            $manifest_data = $request->session()->get('manifest_data');
           // dd($manifest_data);
            $courier_ids = $manifest_data['courier_ids'];
            $session_items = isset($manifest_data['items'])?$manifest_data['items']:"";
            $session_bulk_items = isset($manifest_data['bulk_items'])?$manifest_data['bulk_items']:"";
            $manifest = new Manifest();
            $manifest->vendor_id = $input['vendor_id'];
            $manifest->created_by = \Auth::user()->id;
            $manifest->created_user_type = \Auth::user()->user_type;
            $manifest->unique_name=$this->getManifestUniqueName();
            $manifest->courier_ids=implode(",",$courier_ids);
            $manifest->content=json_encode($manifest_data);
            $manifest->save();
            $manifest_id = $manifest->id;
            if(!empty($session_items)){

                foreach ($session_items as $si){
                    $manifest_item = new Manifest_item();
                    $manifest_item->manifest_id = $manifest_id;
                    $manifest_item->item_type = 'item';
                    $manifest_item->courier_id = $si;
                    $manifest_item->save();
                }
            }

            if(!empty($session_bulk_items)){

                foreach ($session_bulk_items as $sbi){
                    $manifest_item = new Manifest_item();
                    $manifest_item->manifest_id = $manifest_id;
                    $manifest_item->item_type = 'bulk';
                    $manifest_item->courier_id = implode(",",$sbi);
                    $manifest_item->save();
                }

            }
            // update the courier status shipped
            foreach ($courier_ids as $cid){
               $courier= Courier::find($cid);
               $shipped_status_id = Status::where('code_name',"shipped")->first()->id;
               $courier->status_id = $shipped_status_id;
               $courier->save();
            }
            $request->session()->flash('message', 'Manifest has been added successfully!');
            return redirect('/'.\Auth::user()->user_type.'/manifest');
        }else{
            $request->session()->flash('error_message', 'Please Create Manifest');
            return redirect('/'.\Auth::user()->user_type.'/manifest/create');
        }

    }

    private function getManifestUniqueName(){

        $menifest = Manifest::all();
        $menifest_count =$menifest->count();
        $Mcount=1;
        if($menifest_count > 0){
            $Mcount =  $menifest_count+1;
        }
        return "M0000".$Mcount;
    }



}
