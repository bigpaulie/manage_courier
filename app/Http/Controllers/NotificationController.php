<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;


class NotificationController extends Controller
{

    public function index(){

        $notifications= Notification::orderBy('created_at','desc')->paginate(10);

        $data['notifications']=$notifications;
        return view('admin.notifications.index',$data);
    }

    public function updateNotificationStatus(Request $request){

        $input =$request->all();
        $notification = Notification::find($input['notification_id']);
        $notification->status = $input['status'];
        $notification->save();
    }
}
