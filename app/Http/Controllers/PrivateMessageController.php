<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrivateMessage;
use Auth;
use Redis;

class PrivateMessageController extends Controller
{
    public function getUserNotifications(Request $request)
    {

    	$notifications = PrivateMessage::where('read', 0)
    									->where('receiver_id', $request->user()->id)
    									->orderBy('created_at', 'desc')
    									->get();

		return response()->json($notifications, 200);
    }	

    public function getPrivateMessages(Request $request)
    {
    	$pms = PrivateMessage::where('receiver_id', $request->user()->id)->orderBy('created_at', 'desc')->get();

		return response()->json($pms, 200);


    }



    public function getPrivateMessageById($id)
    {
    	$pm = PrivateMessage::where('id', $id)->first();

    		if($pm->read == 0) {
    			$pm->read = 1;
    			$pm->save();
    		}

            return response()->json($pm, 200);
    }


    public function getPrivateMessagesSent(Request $request)
    {
    	$pms = PrivateMessage::where('sender_id', $request->user()->id)->orderBy('created_at', 'desc')->get();

		return response()->json($pms, 200);


    }

    public function sendPrivateMessage(Request $request)
    {
    	$attributes = [
    		'sender_id' =>$request->user()->id,
    		'receiver_id' =>$request->receiver_id,
    		'subject'	=>$request->subject,
    		'message'	=>$request->message,
    		'read'	=> 0
    	];

    	$pm = PrivateMessage::create($attributes);
    	$data = PrivateMessage::where('id', $pm->id)->first();

        $redis = Redis::connection();
        $redis->publish('message', $data);

    	return response()->json($data, 200); 


    }
}
