<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\AddToFavourite;
use App\Favourite;
use Auth;
use App\User;

/*can use only two fields as follower and followed but i am gonna keep it this complex way for some days june 9
    

*/
class FavouriteController extends Controller
{
    public function addToFavourite($followed_id)
    {   
        $favourite = Favourite::where('followed_id', $followed_id)->where('follower_id', Auth::id())->get();

        if(count($favourite) > 0 ) {
            return response()->json(['message'=>'aleady added as favourite artist']);
        }

    	$favourite = Favourite::create([
    		'followed_id' => $followed_id,
            'follower_id' => Auth::guard('web')->id()
        ]);

        $user_id = $favourite->followed_id;
        $user = User::find($user_id);

        $user->notify(new AddToFavourite(Auth::guard('web')->user()));


        return response()->json(['message'=>'succesfuly added in your favourite list ','favorite_id'=>$favourite->id]);

    }

    public function checkFavourite($followed_id)//checking part is done from vuejs for now 
    {
        $favourite = Favourite::where('followed_id', $followed_id)->where('follower_id', Auth::id())->get();

        if(count($favourite) > 0 ) {
            return 1;
        }
    }

    public function getAllFavourite()
    {
    	$favourite = Favourite::where('follower_id', Auth::id())->get();
    	return response()->json($favourite);

    }

    public function removeFavourite($followed_id)
    {   
        $favourite = Favourite::where('followed_id', $followed_id)->where('follower_id', Auth::id())->first();
        $favourite->delete();

        return response()->json(['message'=>'succesfully removed from your favourite list','favorite_id'=>$favourite->id]);

    }
    
/*    public function checkFavouriteFan($fan_id)
    {
        $favourite = Favourite::where('fan_id', $fan_id)->where('follower_id', Auth::id())->get();

        if(count($favourite) > 0 ) {
            return 1;        
        }    
    }
*/

    /*public function addToFavouriteFan($fan_id)
    {   
        $favourite = Favourite::where('fan_id', $fan_id)->where('follower_id', Auth::id())->get();

        if(count($favourite) > 0 ) {
            return response()->json(['message'=>'aleady added as favourite fan']);
        }
    
        $favourite = Favourite::create([
            'fan_id'=>$fan_id,
            'artist_id'=>Auth::guard('web')->id(),
            'follower_id'=>Auth::guard('web')->id()

        ]);

        $user_id = $favourite->fan_id;

        $user = User::find($user_id);

        $user->notify(new AddToFavourite(Auth::guard('web')->user()));

        return response()->json(['message'=>'succesfully added as favourite fan','favorite_id'=>$favourite->id]);

    }*/



    /*public function removeFavouriteArtist($artist_id)
    {   
        $favourite = Favourite::where('artist_id', $artist_id)->where('follower_id', Auth::id())->first();
        $favourite->delete();

        return response()->json(['message'=>'succesfully removed from favourite artists List','favorite_id'=>$favourite->id]);

    }*/
}
