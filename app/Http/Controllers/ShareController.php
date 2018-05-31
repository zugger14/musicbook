<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Share;
use App\Song;
use Auth;


class ShareController extends Controller
{
    public function share($song_id)
    {
    	$song = Song::find($song_id);
        $shared_check = Share::where('song_id', $song_id)->where('user_id', Auth::id())->get();
        if(count($shared_check) > 0) {
            return 0;
        }

    	$share = Share::create([
    		'user_id'=>Auth::guard('web')->id(),
    		'song_id'=>$song->id

    	]);

    	return Share::find($share->id);

    }

    public function unshare($song_id)
    {
    	$song = Song::find($song_id);

    	$share = Share::where('user_id', Auth::guard('web')->id())
    		  ->where('song_id', $song->id)
    		  ->first();
    	
    	$share->delete();

    	return $share;

    }
}
