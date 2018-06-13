<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NewFriendRequest;
use App\Notifications\FriendRequestAccepted;
use Auth;
use Redis;
use App\User;

class FriendshipsController extends Controller
{
    public function checkStatus($user_id)
    {

    	if(Auth::user()->isFriendWith($user_id)===true) {
    		return response()->json(['status' => 'friends']);
    	}

    	if(Auth::user()->hasPendingRequestFrom($user_id)===true) {
    		return response()->json(['status' => 'pending']);
    	}

    	if(Auth::user()->hasPendingRequestTo($user_id)===true) {
    		return response()->json(['status' => 'waiting']);
    	}

    	return response()->json(['status' => 0]);
    }

    public function addFriend($user_id)
    {
        //return 'hey';
        $resp = Auth::user()->addFriend($user_id);
        if($resp === true) {
            $user = User::find($user_id);
            $user->notify(new NewFriendRequest(Auth::guard('web')->user()) );

            $redis = Redis::connection();

            $user_data = Auth::user();
            $user_data->intended_userid = $user_id;

            $redis->publish('addfriend', $user_data);
        }
        return response()->json($resp);
    }

    public function acceptFriend($user_id)
    {
        $resp = Auth::user()->acceptFriend($user_id);
        if($resp === 1) {
            $user = User::find($user_id);
            $user->notify(new FriendRequestAccepted(Auth::guard('web')->user()) );
            $redis = Redis::connection();

            $user_data = Auth::user();
            $user_data->intended_userid = $user_id;

            $redis->publish('acceptfriend', $user_data);
        }
        return $resp;
    }

    public function removeFriend($user_id)
    {   
        $resp = Auth::user()->removeFriend($user_id);
        return $resp;
    }

    public function getPendingRequests()
    {
        $pending_users = Auth::user()->pendingRequest();
        return $pending_users;

    }

    public function removePendingRequest($user_id)
    {   
        $resp = Auth::guard('web')->user()->removePendingRequest($user_id);
        return $resp;

    }

    public function getFriends($user_id)
    {
        $user = User::find($user_id);
        $friends = $user->friends();
        return $friends;
    }

    public function getSentRequests()
    {
        $sent_users = Auth::user()->PendingRequestSent();
        return $sent_users;

    }

     public function removePendingRequestSent($user_id)
    {
        $resp = Auth::user()->removePendingRequestSent($user_id);
        return $resp;

    }


}
