<?php

namespace App\Http\Controllers;

use App\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;
use File;
use Response;
use App\Order;
use Auth;

class SongController extends Controller
{
    /**
     * Display a listing of the songs for demo listening .
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$this->songFeeds();
    }


    /**
     * allow the user to have full song and access to download full song after payment process.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrivateSong($song_id)
    {
        //check if user id present in order before giving them song access.
        $songs = Song::whereId($song_id)->where('upload_type', 'private')->get(); // to get array collection because vueplayer wants array in song view component
        foreach ($songs as $song) {
            //if($song->upload_type == 'private'){
                $music_file = asset('storage/songs') . '/' .$song->song_filename;
                $song->src = $music_file;   
            //}
        }
        return $songs; //automatically returns as response type
    }

    public function getPrivateSongDemo($artist_id )
    {
        $songs = Song::where('user_id', $artist_id)->where('upload_type', 'private')->orderBy('id', 'desc')->get();
        $getID3 = new \getID3();
        foreach ($songs as $song) {
            //$fileContents = File::get($music_file);
            $music_file = public_path('storage/songs/') . $song->song_filename;
            // return response($music_file);
            $id3_info = $getID3->analyze($music_file);
                                     /*$id3_info = json_encode($id3_info);
                                        return response($id3_info['playtime_string']); 
*/          list($t_min, $t_sec) = explode(':', $id3_info['playtime_string']);  

            $time = ($t_min * 60) + $t_sec;  

            $preview = $time / 10; // Preview time of 3-4 seconds  

            $handle = fopen($music_file, 'r');

            $content = fread($handle, filesize($music_file));  
            //$totallength = strlen($content);  
            $length = round(strlen($content) / $preview);
            $start_length = $length * 0.1;//starts extracting length about 0.1 percent of total length playtime
            $contents = base64_encode(substr($content, $start_length, $length));
            //$contents = base64_encode($content);
            $song->src ='data:audio/mp3;base64,' . $contents;

            /*for ($length;$length < $totallength;$length = $length*2// condition is not right to find out the next length of a song
            )  {
                $content = substr($content, $start_length, $length);
                $start_length = $length;
                //$contentl = strlen($content);
                $contents = base64_encode($content);
                $src[] ='data:audio/mp3;base64,' . $contents;
            }
*/            
            //$song->src = $src;

        }
           /*$mime_type = $id3_info['mime_type'];   
            $headers = array(
                'Accept-Ranges: 0-' . (filesize($music_file) -1) ,

                'Content-Length:'.$length,
                'Content-Type:' . $mime_type,
                'Content-Disposition: inline; filename="'.$music_file.'"'
            );
*/          /*  $songnew = $songs->map(function ($song) use ($contents){
                $song['file'] = $contents; brings out same content for all  song objects 
                return $song;
            });
*/
        return Response::make($songs, 200);
        //return response()->json($songs)->header($headers);
       // return response()->json($content)->header('Content-Type', $id3_info['mime_type'])->header('Content-Length', $length);

        //$length = round(strlen($content) / $preview);  
       // $content = substr($content, $length * .66 /* Start extraction ~20 seconds in */, $length);  

        /*header("Content-Type: {$id3_info['mime_type']}");  
        header("Content-Length: {$length}"); */ 
        //return response($content)->header('Content-Type', $id3_info['mime_type'])->header('Content-Length', $length);

        //return response(storage_path());
       // $songs['storage'] = storage_path();
        //return response()->json($songs);

    }

    public function songFeeds() //returns all song feed for users from thier added friends
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
           //return $songfeeds;
        }

        return response()->json($songfeeds); //aleady returns as response only but not json i guess

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:mpga,wav',
            'filename' => 'required|max:255',
            'filesize' => 'sometimes|max:2048000',
            'description' => 'sometimes|max:255',
            'img' => 'nullable',
            'upload_type' =>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {

            $song = new Song;
            $song->title = $request->filename;
            $song->user_id = Auth::guard('web')->id();
            $song->upload_type = $request->upload_type;
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
                $location = storage_path('app/public/images/songcovers');  // add artists id name anything here for folder structure.
                $image_file->move($location,$filename);
                
                $song->image = $filename;
            } 
            
            $song->song_description = $request->description;
            $song->save();

            return response()->json("Successfully uploaded your song. ");

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
    public function update(Request $request, Song $song)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        //
    }

    public function upload()
    {
        return view('songs.create');

    }

    public function addToOrderList(Request $request)
    {
        $order = new Order;

        $order->total_amount = 100;
        $order->user_id = Auth::guard('web')->id();
        $order->save();
        return view('songs.pay-confirm')->withOrder($order);

    }


}
