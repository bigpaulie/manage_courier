<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;
use App\Models\Status;
use App\Models\Vendor;
use App\Models\Manifest;
use App\Models\Manifest_item;
use App\Models\Manifest_bulk_payment;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ManifestExport;
use App\Exports\ManifestDetailExport;
use App\Models\Company;
use App\Models\Country;




use Session;
use Validator;


class ManifestController extends Controller
{

    public function index(){

        if(\Auth::user()->user_type == 'admin'){

            $manifests=Manifest::orderBy('created_at', 'desc')->get();
        }
        if(\Auth::user()->user_type == 'store'){

            $logged_user_id = \Auth::user()->id;
            $manifests=Manifest::where('created_by',$logged_user_id)
                                ->orderBy('created_at', 'desc')->get();
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
    public function create(Request $request)
    {
        $status_id = Status::where('code_name',"pending")->first()->id;
        $agent_id= isset($request->agent_id)?$request->agent_id:0;
        $from_date= isset($request->from_date)?$request->from_date:"";
        $end_date= isset($request->end_date)?$request->end_date:"";

        $user_type = \Auth::user()->user_type;
        $courier_joins = Courier::with(['agent','status','receiver_country']);
        if($user_type == 'admin'){
            if(!empty($agent_id) && $agent_id > 0  && $from_date !="" && $end_date != "") {

                $couriers = $courier_joins
                    ->where('status_id', $status_id)
                    ->where('user_id', $agent_id)
                    ->whereDate('courier_date', '>=', date('Y-m-d', strtotime($from_date)))
                    ->whereDate('courier_date', '<=', date('Y-m-d', strtotime($end_date)))
                    ->OrderBy('courier_date', 'desc')->get();

            }else if($from_date !="" && $end_date != ""){

                $couriers = $courier_joins
                     ->where('status_id', $status_id)
                     ->whereDate('courier_date', '>=', date('Y-m-d', strtotime($from_date)))
                    ->whereDate('courier_date', '<=', date('Y-m-d', strtotime($end_date)))
                    ->OrderBy('courier_date', 'desc')->get();

            }else{
                $couriers= $courier_joins
                    ->where('status_id',$status_id)
                    ->OrderBy('courier_date','desc')->get();
            }

        }else if($user_type == 'store'){

            $agents_id= User::where('user_type','agent')
                ->whereHas('profile', function ($query) {
                    $query->where('store_id',\Auth::user()->id);
                })->pluck('id')->toArray();

            if(!empty($agent_id) && $agent_id > 0) {
                $agents_id = [$agent_id];
            }else{
                $agents_id = array_prepend($agents_id, \Auth::user()->id);
            }

            $couriers= $courier_joins
                ->where('status_id',$status_id)
                ->whereIn('user_id',$agents_id)
                ->whereDate('courier_date','>=', date('Y-m-d',strtotime($from_date)))
                ->whereDate('courier_date', '<=',date('Y-m-d',strtotime($end_date)))
                ->OrderBy('courier_date','desc')->get();
        }



        if(!empty($agent_id) && $agent_id > 0) {
            $data['agent'] = User::find($agent_id);
        }
        $data['vendors']=Vendor::pluck('name','id')->toArray();
        $data['companies']=Company::all();
        $data['couriers'] =$couriers;
        $data['from_date'] =$from_date;
        $data['end_date'] =$end_date;
       // dd(Session::get('manifest_data'));
        return view('admin.manifest.create',$data);

    }


    public function edit($id)
    {
        $data['manifest']=Manifest::find($id);
        $data['vendors']=Vendor::pluck('name','id')->toArray();
        return view('admin.manifest.edit',$data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
            'amount' => 'required',
            'manifest_date' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect('/'.\Auth::user()->user_type.'/manifest/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $manifest = Manifest::find($id);
        if($manifest != null){
            $manifest->vendor_id = $input['vendor_id'];
            $manifest->amount = $input['amount'];
            $manifest->manifest_date = date('Y-m-d',strtotime($input['manifest_date']));
            $manifest->save();
        }
        $request->session()->flash('message', 'Manifest has been updated successfully!');
        return redirect('/'.\Auth::user()->user_type.'/manifest');
    }


    public function destroy($id)
    {
        $manifest = Manifest::where('id',$id)->first();
        $courier_ids = explode(",",$manifest->courier_ids);
        $pending_status_id = Status::where('code_name',"pending")->first()->id;
        $manifest_couriers = Courier::whereIn('id',$courier_ids)->update(['status_id' => $pending_status_id]);
        Manifest_item::where('manifest_id',$id)->delete();
        Manifest::where('id',$id)->delete();


        \Session::flash('message', 'Manifest has been deleted successfully!');
        return redirect('/'.\Auth::user()->user_type.'/manifest');
    }


    public function show($id){

        $manifest= Manifest::find($id);
        if($manifest != null){
            $manifest_items = $manifest->manifest_items->where('item_type','item')->toArray();
            $manifest_bulks = $manifest->manifest_items->where('item_type','bulk')->toArray();
            $data['manifest']=$manifest;
            $data['manifest_items']=$manifest_items;
            $data['manifest_bulks']=$manifest_bulks;
           // dd($data);
            return view('admin.manifest.show',$data);
        }else{
            abort(404);
        }
    }

    public function printManifest($id){

        $manifest= Manifest::find($id);
        if($manifest != null){
            $courier_ids = explode(",",$manifest->courier_ids);
            $manifest_couriers = Courier::whereIn('id',$courier_ids)->get();
            $data['manifest']=$manifest;
            $data['manifest_couriers']=$manifest_couriers;

            return view('admin.manifest.print',$data);
        }else{
            abort(404);
        }
    }
    public function createManifest(Request $request){


        $input= $request->all();
        $from_date = $input['from_date'];
        $end_date = $input['end_date'];
        $agent_id = isset($input['filter_agent_id'])?$input['filter_agent_id']:'';
        $courier_ids = $input['courier_id'];
        $manifest_data=[];
        $company_id= $input['company_id'];


        $session_exists=0;
        if(Session::has('manifest_data')){
            $session_manifest_data = $request->session()->get('manifest_data');
            $session_courier_ids =$session_manifest_data['courier_ids'];
            $courier_ids= array_merge($courier_ids,$session_courier_ids);
            $session_exists=1;

            if($company_id > 0){
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

        if($company_id > 0){

            if($session_exists && isset($session_manifest_data['bulk_items'])){
                $session_bulk_items =$session_manifest_data['bulk_items'];
                $count_bulk_items = count($session_bulk_items);
                $new_bulk_item[$count_bulk_items]['courier_ids'] = $input['courier_id'];
                $new_bulk_item[$count_bulk_items]['company_id']=$input['company_id'];

                $manifest_data['bulk_items']=array_merge($session_bulk_items,$new_bulk_item);

            }else{
                $manifest_data['bulk_items'][]['courier_ids']=$input['courier_id'];
                $manifest_data['bulk_items'][0]['company_id']=$input['company_id'];
            }
        }
           // dd($session_exists);
           // dd($manifest_data);

        $request->session()->put('manifest_data', $manifest_data);

       // return redirect()->route('manifest.create');
        if(!empty($from_date) && !empty($end_date) && $agent_id > 0){
            return redirect('/'.\Auth::user()->user_type.'/manifest/create?agent_id='.$agent_id.'&from_date='.$from_date.'&end_date='.$end_date);

        }else if(!empty($from_date) && !empty($end_date)){
            return redirect('/'.\Auth::user()->user_type.'/manifest/create?agent_id=null&from_date='.$from_date.'&end_date='.$end_date);
        }else{
            return redirect('/'.\Auth::user()->user_type.'/manifest/create');

        }



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
            $manifest->amount = $input['amount'];
            $manifest->manifest_date = date('Y-m-d',strtotime($input['manifest_date']));
            $manifest->payment_date = date('Y-m-d');
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
                    $manifest_item->courier_id = implode(",",$sbi['courier_ids']);
                    $manifest_item->company_id = $sbi['company_id'];
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

    public function downloadManifest(){


        if(\Auth::user()->user_type == 'admin'){

            $manifests=Manifest::get();
        }
        if(\Auth::user()->user_type == 'store'){

            $logged_user_id = \Auth::user()->id;
            $manifests=Manifest::where('created_by',$logged_user_id)->get();
        }

        $t="manifests_".time().".xlsx";
        return Excel::download(new ManifestExport($manifests), $t);


    }

    public function excelManifest($id){

        $manifest= Manifest::find($id);
        $manifest_items = $manifest->manifest_items;
       // dd($manifest_items);
        if($manifest != null){
            //$courier_ids = explode(",",$manifest->courier_ids);
           // $manifest_couriers = Courier::whereIn('id',$courier_ids)->get();


            $manifest_details =[];

            foreach ($manifest_items as $mi){

                $courier_ids = explode(",",$mi->courier_id);
                $manifest_couriers = Courier::whereIn('id',$courier_ids)->get();
                $manifest_weight =0;
                $no_of_boxes=0;
                foreach ($manifest_couriers as $mc){
                    $manifest_weight+= $mc->shippment->weight;
                    $no_of_boxes+= $mc->no_of_boxes;
                }

                $countries = Country::pluck('name', 'id')->toArray();
                $temp =[];
                $temp['unique_name']=$manifest->unique_name;
                if($mi->item_type == 'bulk'){
                    $temp['sender_name']=$manifest->store->name;
                    $temp['recipient_name']=$mi->company->name;
                    $temp['source']=$countries[$manifest->store->profile->country_id];
                    $temp['destination']=$mi->company->address;
                }else if($mi->item_type == 'item'){
                    $temp['sender_name']=$manifest_couriers[0]->s_name;
                    $temp['recipient_name']=$manifest_couriers[0]->r_name;
                    $temp['source']=$countries[$manifest_couriers[0]->s_country];
                    $temp['destination']=$countries[$manifest_couriers[0]->r_country];
                }

                $temp['weight']=$manifest_weight;
                $temp['no_of_boxes']=$no_of_boxes;
                $temp['amount']=0;

                $manifest_details[]=$temp;
            }
           // dd($manifest_details);
            $data['manifest']=$manifest;
            $data['manifest_details']=$manifest_details;

            $t="manifest_details_".time().".xlsx";
            return Excel::download(new ManifestDetailExport($data), $t);
        }else{
            abort(404);
        }
    }


    public function bulkPayment($id){

        $manifest= Manifest::find($id);
        $manifest_items = $manifest->manifest_items->where('item_type','bulk');

        if($manifest != null){
           $manifest_details =[];

            foreach ($manifest_items as $mi){

                $courier_ids = explode(",",$mi->courier_id);
                $manifest_couriers = Courier::whereIn('id',$courier_ids)->get();
                $manifest_weight =0;
                $no_of_boxes=0;
                foreach ($manifest_couriers as $mc){
                    $manifest_weight+= $mc->shippment->weight;
                    $no_of_boxes+= $mc->no_of_boxes;
                }

                $countries = Country::pluck('name', 'id')->toArray();
                $temp =[];
                $temp['unique_name']=$manifest->unique_name;
                if($mi->item_type == 'bulk'){
                    $temp['sender_name']=$manifest->store->name;
                    $temp['recipient_name']=$mi->company->name;
                    $temp['source']=$countries[$manifest->store->profile->country_id];
                    $temp['destination']=$mi->company->address;
                    $temp['item_id']=$mi->id;
                    $temp['bulk_payment']=$mi->bulk_payment;;
                }

                $temp['weight']=$manifest_weight;
                $temp['no_of_boxes']=$no_of_boxes;


                $manifest_details[]=$temp;
            }
             //dd($manifest_details);
            $data['manifest']=$manifest;
            $data['manifest_details']=$manifest_details;
            return view('admin.manifest.bulk_payment',$data);

        }else{
            abort(404);
        }
    }

    public function saveBulkPayment(Request $request){

        $bulk_payment_data = $request->all();

        foreach ($bulk_payment_data['manifest'] as $bp){


            $manifest_bulk_payment = Manifest_bulk_payment::where('manifest_id',$bp['manifest_id'])
                                                            ->where('manifest_item_id',$bp['item_id'])
                                                            ->first();

            if($manifest_bulk_payment != null){

                $manifest_bulk_payment->manifest_id = $bp['manifest_id'];
                $manifest_bulk_payment->manifest_item_id = $bp['item_id'];
                $manifest_bulk_payment->amount = $bp['bulk_payment'];
                $manifest_bulk_payment->payment_date = date('Y-m-d');
                $manifest_bulk_payment->save();

            }else{
                $manifest_bulk_payment = new Manifest_bulk_payment();
                $manifest_bulk_payment->manifest_id = $bp['manifest_id'];
                $manifest_bulk_payment->manifest_item_id = $bp['item_id'];
                $manifest_bulk_payment->amount = $bp['bulk_payment'];
                $manifest_bulk_payment->payment_date = date('Y-m-d');
                $manifest_bulk_payment->save();
            }



            $manifest_item = Manifest_item::find($bp['item_id']);
            $manifest_item->bulk_payment = $bp['bulk_payment'];
            $manifest_item->save();

        }

        $request->session()->flash('message', 'Bulk Payment has been added successfully!');
        return redirect('/'.\Auth::user()->user_type.'/manifest');

    }



}
