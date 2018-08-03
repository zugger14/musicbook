<?php

namespace App\Http\Controllers;

use App\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Notifications\NewSongSale;
use Notification;
use Validator;
use File;
use Response;
use App\Order;
use App\User;
use App\Like;
use App\Favourite;
use Auth;
use Paypalpayment;
use Carbon\Carbon;
use DB;
use Image;

class SongController extends Controller
{
    /**
     * Display a listing of the songs for demo listening .
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Song::all();
        return view('admins.show_songs')->withSongs($songs);
    }

    public function artistSongs()
    {   
        $artistIds = User::where('is_artist', 1)->pluck('id');
        $songs = Song::whereIn('user_id',$artistIds)->get();
        return view('admins.show_artistsongs')->withSongs($songs);
    }

    public function fanSongs()
    {
        $fanIds = User::where('is_artist', 0)->pluck('id');
        $songs = Song::whereIn('user_id',$fanIds)->get();
        return view('admins.show_fansongs')->withSongs($songs);
    }

    public function deletedSongs()
    {

        $songs = Song::where('status','deleted')->get();
        return view('admins.show_deletedsongs')->withSongs($songs);
    }


    /**
     * allow the user to have full song and access to download full song after payment process.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrivateSong($song_id)//used to fetch privates song fro download to users who bought the song...
    {
        //check if user id present in order table before giving them song access.
        $user_id = Auth::guard('web')->id();
        $orders = Order::where('user_id', $user_id)->where('song_id', $song_id)->get();
        foreach ($orders as $order) {
            if($order->paid_order == 1){
                $songs = Song::whereId($song_id)->where('upload_type', 'private')->get(); // to get array collection because vueplayer wants array in song view component
                foreach ($songs as $song) {

                        $music_file = asset('storage/songs') . '/' .$song->song_filename;
                        $song->src = $music_file;   
                }                       
                return $songs;
            }
        }
        return 'please purchase before accesing this song';
    }

    public function getPublicSong($song_id)
    {
        $songs = Song::where('id', $song_id)->where('status', 'present')->get();
        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }     
        return $songs;
        
    }

    public function getPrivateSongDemoBySongId($song_id )//loads single demo songs for shared private songs.
    {

        $song = Song::where('id', $song_id)->where('upload_type', 'private')->where('status', 'present')->first();
        $getID3 = new \getID3();
        $music_file = public_path('storage/songs/') . $song->song_filename;
        $id3_info = $getID3->analyze($music_file);

        list($t_min, $t_sec) = explode(':', $id3_info['playtime_string']);  

        $time = ($t_min * 60) + $t_sec;  
        $preview = $time / 10; // Preview time of 10 seconds  
        $handle = fopen($music_file, 'r');
        $content = fread($handle, filesize($music_file));  
        $length = round(strlen($content) / $preview);
        $start_length = $length * 0.1;
        $contents = base64_encode(substr($content, $start_length, $length));
        $song->src ='data:audio/mp3;base64,' . $contents;

        return $song;

    }


    public function getPrivateSongDemo($artist_id )//loads all demo songs for sale purpose by individual artist
    {
        $songs = Song::where('user_id', $artist_id)->where('upload_type', 'private')->where('status', 'present')->orderBy('id', 'desc')->get();
        $getID3 = new \getID3();
        foreach ($songs as $song) {

            $music_file = public_path('storage/songs/') . $song->song_filename;
            $id3_info = $getID3->analyze($music_file);

            list($t_min, $t_sec) = explode(':', $id3_info['playtime_string']);  

            $time = ($t_min * 60) + $t_sec;  

            $preview = $time / 10; // Preview time of 10 seconds  

            $handle = fopen($music_file, 'r');

            $content = fread($handle, filesize($music_file));  
            //$totallength = strlen($content);  
            $length = round(strlen($content) / $preview);
            $start_length = $length * 0.1;//starts extracting length about 0.1 percent of total length playtime
            $contents = base64_encode(substr($content, $start_length, $length));
            $song->src ='data:audio/mp3;base64,' . $contents;
        }

        return Response::make($songs, 200);
    }

    public function viewDownSong($payment_id)//used to show download song page after paypal payment success
    {   
        try {
            $payment = Paypalpayment::getById($payment_id, Paypalpayment::apiContext());
        } catch(PayPalConnectionException $e) {
            return $e->getMessage();
        }
        
        $order = Order::where('paypal_paymentid', $payment_id)->where('user_id', Auth::id())->get();

        foreach ($order as $or) {
            if($or->paid_order == 1) {
                $song_id = $or->song_id;
                return view('songs.download')->withPayment($payment->toArray())->withSong($song_id);
            }        
        }
        return 'you dont have access to view download';
    }

    public function getUserSongs($user_id)//get all public songs of a particular user maybe fan or artist by id
    {
        $songs = Song::where('user_id', $user_id)->where('status', 'present')->where('upload_type', 'public')->orderBy('id', 'desc')->get();//if used all() instead get canot read value in vuejs by aplayer 
        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return response()->json($songs, 200);//json response can be used sending to vuejs only doesnot work if sent to blade files(protected json response error) but can send jsonecode without response thats the difference of response..
    }

    public function getPurchasedSongs()//gets all purchased songs by user id
    {   
        $user_id = Auth::id();
        $orders = Order::where('user_id', $user_id)->where('paid_order', 1)->get();
        $songs_id = $orders->pluck('song_id');

        $songs = Song::whereIn('id', $songs_id)->get();

        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return $songs;

    }


    public function showLikedSongPage($user_id)
    {
        return view('artists.likedsong')->with('user_id', $user_id);
    } 

    public function getLikedSongs($user_id)
    {   
        $user = User::find($user_id);
        $likes = $user->like;
        $songs = array();

        foreach ($likes as $like) {//also only show public songs that are liked...private songs like features should be removed lets think ;ater..
            $songs[] = $like->song;//needs [] even declared as array above FFFFF
        }

        foreach ($songs as &$song) {
            if($song->upload_type == 'private' && $song->status == 'present') {
                $song = $this->getPrivateSongDemoBySongId($song->id);
            } else {
                $music_file = asset('storage/songs') . '/' .$song->song_filename;
                $song->src = $music_file;   
            }
        }

        return $songs;
    }

    public function getSharedSongs($user_id)
    {
        $user = User::find($user_id);
        $shares = $user->share;
        $songs = array();

        foreach ($shares as $share) {
            $songs[] = $share->song;
        }

        foreach ($songs as &$song) {
            if($song->upload_type == 'private' && $song->status == 'present') {
                $song = $this->getPrivateSongDemoBySongId($song->id);//to replace $song object wihtn new object pass referene in foreach
            } else {
                $music_file = asset('storage/songs') . '/' .$song->song_filename;
                $song->src = $music_file;   
            }
        }

        //dd($songs);
        return $songs;

    } 

    public function songFeeds() //returns all song feeds of users and their friends(basically public songs uploaded ) for users from thier added friends
    {      
        $friends = Auth::guard('web')->user()->friends();
        $songfeeds = array();
        
        foreach ($friends as $friend) {
            foreach ($friend->songs as $song) {
                if($song->upload_type == 'public') {
                    $song->src = asset('storage/songs') . '/' .$song->song_filename;// or change in frontend more easy i think so
                    array_push($songfeeds, $song);
                }
            }
        }

        $sharedfeeds = array();
        $friendsId = Auth::guard('web')->user()->friendsId();

        foreach ($friendsId as $fid) {
            $shared_songs = $this->getSharedSongs($fid);
            foreach ($shared_songs as $song) {
                $song->shared = true;
                array_push($sharedfeeds, $song);
            }
        }
        
        $songfeeds = array_merge($songfeeds, $sharedfeeds);
        $songfeeds = array_reverse(array_values(array_sort($songfeeds, function ($value) {
            return new Carbon($value['created_at']);
        })));

        //$unique = collect($songfeeds)->unique();
        return response()->json($songfeeds);
    }

    /**
     * single file upload
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
   
    public function store(Request $request)
    {//max amount for pricing song would be 10000
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:mpga,wav|max:20000',
            'filename' => 'required|max:255',
            'filesize' => 'required|numeric|max:20',
            'description' => 'sometimes|max:255',
            'img' => 'nullable',
            'upload_type' =>'required',
            'amount'    =>'sometimes|numeric|max:10000',
            'tags'      =>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {

            $song = new Song;
            $song->title = $request->filename;
            $song->user_id = Auth::guard('web')->id() || Auth::guard('admin')->id();
            $song->upload_type = $request->upload_type;


            $song->status = 'present';

            if ($request->hasFile('file')) {
                $music_file = $request->file('file');

                $filename = time() . '.' . $music_file->getClientOriginalExtension();
                $location = storage_path('app/public/songs');  // add artists id name anything here for folder structure.
                $music_file->move($location,$filename);
                
                $song->song_filename = $filename;

            } else {
                return response()->json("no audio file");
            }

            if ($request->hasFile('img')) {
                $image_file = $request->file('img');
                $filename = time() . '.' . $image_file->getClientOriginalExtension();
                $location = storage_path('app/public/images/songcovers/');  // add artists id name anything here for folder structure.
                Image::make($image_file)->resize(800,400)->save($location . $filename);
                //$image_file->move($location,$filename);without resizing
                $song->image = $filename;
            } 
            
            $song->amount = $request->amount;
            $song->song_description = $request->description;
            $song->save();
            $tags = json_decode($request->tags);
            $tags_id = collect($tags)->pluck('id');
            // return $tags_id;
            $song->tags()->sync($tags_id, false);
            
            if($song->upload_type == 'private') {    
                $favourites = Favourite::where('followed_id', Auth::id())->where('follower_id', '!=', Auth::id())->get();
                $users = array();
                foreach ($favourites as $favourite) {
                    $users[] = $favourite->follower;
                }
                if (!empty($users))
                    Notification::send($users, new NewSongSale(Auth::user() ,$song));
            }

            return response()->json(['message' =>"Successfully uploaded your song. "]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Song  $song
     * @return \Illuminate\Http\Response
     */

    public function show(Song $song)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function edit(Song $song)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'nullable|max:255',
            'img' => 'nullable',
            'upload_type' =>'required',
            'amount'    =>'sometimes|numeric|max:10000',
            'tags'      =>'required'

        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {

            $song = Song::find($id);
            $song->title = $request->title;
            $song->upload_type = $request->upload_type;

            if ($request->hasFile('img')) {
                $image_file = $request->file('img');
                $filename = time() . '.' . $image_file->getClientOriginalExtension();
                $location = storage_path('app/public/images/songcovers');  // add artists id name anything here for folder structure.
                Image::make($image_file)->resize(800,400)->save($location . $filename);
                //$image_file->move($location,$filename);
                
                $song->image = $filename;
            } 
            
            $song->amount = $request->amount;
            $song->song_description = $request->description;
            $song->save();

             if(isset($request->tags)){ //required atleast one if made optional then if else part is needed.
                $tags = json_decode($request->tags);
                $tags_id = collect($tags)->pluck('id');
                //return $tags_id;
                $song->tags()->sync($tags_id,true);

            } else {

                $song->tags()->sync(array());   //not necesarry but useful to make tags selection optional..
            }

            return response()->json("Successfully edited your song. ");
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Song  $song
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $song = Song::find($id);
        if($song->upload_type == 'private') {
            $song->status = 'deleted';
        } else {

            $song->status = 'deleted';
            //$song->tags()->detach();
            //$song->delete();//here also we can just change status to removed and free up space by deleting file and giving message to users about deleted info
        }
        
        $song->save();
        return response()->json('succesfully deleted song');
    }

    public function addSongPlayedTime($song_id)
    {
        $song = Song::find($song_id);
        $song->played_time ++;
        $song->save();

        return $song->id;
    }

    public function getMostPlayedSongs()
    {
        $songs = Song::orderBy('played_time', 'desc')->where('upload_type', 'public')->where('status', 'present')->limit(20)->get();
        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return $songs;
    }

    public function getMostPlayedUserSongs($user_id)
    {
        $songs = Song::orderBy('played_time', 'desc')->where('upload_type', 'public')->where('status', 'present')->where('user_id', $user_id)->limit(20)->get();
        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return $songs;
    }

    public function getMostSoldSongs()
    {
        $songsByCount = DB::table('orders')
         ->select(array('song_id', DB::raw('count(song_id) as fieldcount')))//select(*) not selecting song_id for grouping below groupby
         ->groupBy('song_id')
         ->orderBy('fieldcount', 'desc')
         ->where('paid_order', true)
         ->get();

        $songs = Song::whereIn('id', $songsByCount->pluck('song_id'))->get();
                
        foreach ($songs as $song) {
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return $songs;    
    }

    public function getMostSoldUserSongs($user_id)
    {   
        /*https://stackoverflow.com/questions/37951742/1055-expression-of-select-list-is-not-in-group-by-clause-and-contains-nonaggr/42183702*/
        $songsByCount = DB::table('orders')
                 ->select(array('song_id', DB::raw('count(song_id) as fieldcount')))//select(*) not selecting song_id for grouping below groupby
                 ->groupBy('song_id')
                 ->orderBy('fieldcount', 'desc')
                 ->where('paid_order', true)
                 ->get();

        $songs = Song::whereIn('id', $songsByCount->pluck('song_id'))->where('user_id', $user_id)->where('status', 'present')->get();
        
        $songs = $songs->map(function ($song) {
            if($song->upload_type == 'private' && $song->status == 'present') {
                $song = $this->getPrivateSongDemoBySongId($song->id);//to replace $song object wihtn new object returning from called method.
                return $song;
            } else {
                $music_file = asset('storage/songs') . '/' .$song->song_filename;
                $song->src = $music_file;
                return $song;   
            }
        });

        /*  
            when collections instance is neede to replace with other foreach and each does not work but works in array of objects.[{}]
            
            $songs = $songs->each(function ($song, $key) {
                if($song->upload_type == 'private' && $song->status == 'present') {
                    $song = $this->getPrivateSongDemoBySongId($song->id);//to replace $song object wihtn new object pass referene in foreach
                    return $song;
                } else {
                    $music_file = asset('storage/songs') . '/' .$song->song_filename;
                    $song->src = $music_file;
                    return $song;   
                }
            });    

        */   

        /*
            foreach ($songs as &$song) {
                if($song->upload_type == 'private' && $song->status == 'present') {
                    $song = $this->getPrivateSongDemoBySongId($song->id);//to replace $song object wihtn new object pass referene in foreach
                } else {
                    $music_file = asset('storage/songs') . '/' .$song->song_filename;
                    $song->src = $music_file;   
                }
            }
        */

        return $songs;
    }



    public function getMostRecentSongs()
    {
        $songs = Song::orderBy('created_at', 'desc')->where('upload_type', 'public')->limit(20)->get();
        foreach ($songs as $song) {//make dry
            $music_file = asset('storage/songs') . '/' .$song->song_filename;
            $song->src = $music_file;   
        }
        return $songs;
    }


    public function addToOrderList(Request $request)
    {
        $order = new Order;
        $order->song_id = $request->id;

        $song = Song::find($request->id);
        if($song->amount == $request->amount) {
            $order->total_amount = $request->amount;
        } else {
            return 'amount for the song is not correct';
        }
        
        $order->user_id = Auth::guard('web')->id();
        $order->paid_order = 0;// set default in db 
        $order->save();

        return response()->json($order , 200);
        
        //return redirect()->route('payment.creditcard');


    }




}
