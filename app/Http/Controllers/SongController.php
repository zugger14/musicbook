<?php

namespace App\Http\Controllers;

use App\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Validator;
use File;
use Response;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
        $songs = Song::orderBy('id', 'desc')->get();
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

            $preview = $time / 30; // Preview time of 30 seconds  

            $handle = fopen($music_file, 'r');  

            $content = fread($handle, filesize($music_file));  
            $length = strlen($content);  

            $length = round(strlen($content) / $preview);  
            $content = substr($content, $length * .60 /* Start extraction ~20 seconds in */, $length);
            $contents = base64_encode($content);
            $song->src ='data:audio/mp3;base64,' . $contents;
           // $mime_type = $id3_info['mime_type'];   
            /*$headers = array(
                'Accept-Ranges: 0-' . (filesize($music_file) -1) ,

                'Content-Length:'.$length,
                'Content-Type:' . $mime_type,
                'Content-Disposition: inline; filename="'.$music_file.'"'
            );*/
        }
          /*  $songnew = $songs->map(function ($song) use ($contents){
                $song['file'] = $contents;
                return $song;
            });
*/
        return Response::make($songs, 200);

        //return response()->json($content)->header('Content-Type', $id3_info['mime_type'])->header('Content-Length', $length);

        //$length = round(strlen($content) / $preview);  
       // $content = substr($content, $length * .66 /* Start extraction ~20 seconds in */, $length);  

        /*header("Content-Type: {$id3_info['mime_type']}");  
        header("Content-Length: {$length}"); */ 
        //return response($content)->header('Content-Type', $id3_info['mime_type'])->header('Content-Length', $length);

        //return response(storage_path());
       // $songs['storage'] = storage_path();
        //return response()->json($songs);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('songs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // $request = json_decode($request->song);
        /*foreach ($request->all() as $value) {
            $song = $value;
        }
        return response()->json($song);*/

       // $songs = $request->song;
       // $songs = json_decode($songs);
      //  return response()->json($request->all());

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:mpga,wav',
            'filename' => 'required|max:255',
            'filesize' => 'sometimes|max:2048000',
            'description' => 'sometimes|max:255',
            'img' => 'nullable'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());

        } else {

            $song = new Song;
            $song->title = $request->filename;
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
}
