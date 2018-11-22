<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courier;

class ReportController extends Controller
{
    public function index(){
        $data=[];
        return view('admin.reports.index',$data);
    }

    public function generateReport(Request $request){

        $input = $request->all();
        $user_id= $input['user_id'];
        $where[] = ['couriers.user_id', $user_id];
        $courier_joins = Courier::with(['agent','status','shippment','courier_charge','receiver_country']);
        $couriers = $courier_joins->where($where)
                      ->OrderBy('updated_at','desc');
        $courier_data = $couriers->paginate(50);

        $total_amount=0;
        $total_pickup_charge=0;
        $total=0;
        if($courier_data->total() > 0 ){
            foreach ($courier_data as $c_data){
                if($c_data->courier_charge != null){
                    $total_amount+=$c_data->courier_charge->amount;
                    $total_pickup_charge+=$c_data->courier_charge->pickup_charge;
                    $total+=$c_data->courier_charge->total;
                }
            }
        }
        $response_data['total_amount']=$total_amount;
        $response_data['total_pickup_charge']=$total_pickup_charge;
        $response_data['total']=$total;
        $response_data['courier_data']=$courier_data;

        return response()->json($response_data);


    }
}
