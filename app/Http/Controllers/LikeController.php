<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Like;
use Auth;

class LikeController extends Controller
{
    public function like($song_id)
    {
    	$song = Song::find($song_id);

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
