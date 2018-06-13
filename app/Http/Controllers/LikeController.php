<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\LikeSong;
use App\Song;
use App\Like;
use Auth;
use App\User;

class LikeController extends Controller
{
    public function like($song_id)
    {
    	$song = Song::find($song_id);
        $user_id = $song->user_id;
        $user = User::find($user_id);
        
        if($user->id != Auth::id()) {
            $user->notify(new LikeSong(Auth::guard('web')->user(), $song));
        }

    	$like = Like::create([
    		'user_id'=>Auth::guard('web')->id(),
    		'song_id'=>$song->id

    	]);

    	return Like::find($like->id);

    }

    public function unLike($song_id)
    {
    	$song = Song::find($song_id);

    	$like = Like::where('user_id', Auth::guard('web')->id())
    		  ->where('song_id', $song->id)
    		  ->first();
    	
    	$like->delete();

    	return $like->id;

    }
}
