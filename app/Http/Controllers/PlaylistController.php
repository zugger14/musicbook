<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\Song;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Album;


class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $playlists = Playlist::all();
        return view('admins.show_playlists')->withPlaylists($playlists);
    }

    //used to show the main playlists page of user as a vuejs component holder
    public function userIndex($user_id)
    {
        //$playlists = Playlist::where('user_id', $user_id)->get();
        return view('artists.playlists')->with('user_id', $user_id);    
    }

    //get all playlists and containing songs of a user
    public function getPlaylistSongs($user_id)
    {   
        if (Auth::id() == $user_id) {
            $playlists = Playlist::where('user_id', $user_id)->get();
        } else {
            $playlists = Playlist::where('user_id', $user_id)->where('private', 0)->get();
        }
        return response()->json($playlists);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'playlist_title' => 'required|unique:playlists',
            'private' => 'nullable'
          ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {

            $playlist = new Playlist;
            $playlist->playlist_title = $request->playlist_title;
            $playlist->user_id = Auth::id();

            $playlist->playlist_type = 'collection';
            
            if($request->private == true) $private = 1;
            else $private = 0;
            $playlist->private = $private;

            $playlist->save();

            return $playlist->id;
        }
    }

    //adds uploaded songs of users to playlist 
    public function addSongToPlaylist(Request $request)
    { 
        //return $request->all();
        /*$song = Song::find($request->song_id);
        $song->playlists()->sync($request->playlist_id);*/
        
        $playlist = Playlist::find($request->playlist_id);
        $playlist->songs()->sync($request->song_id, false);
        
        $playlist->save();

        return 'success in adding song to playlist';


    }

    //multifile upload to palylist
    public function multiStore(Request $request)
    {
        $input_data = $request->all();
        $validator = Validator::make(
            $input_data, [
            'file.*' => 'required|mimes:mpga,wav|max:20000'
            ],[
                'file.*.required' => 'Please upload an audio file',
                'file.*.mimes' => 'Only mp3 and wav audio are allowed',
                'file.*.max' => 'Sorry! Maximum allowed size for an audio file is 20MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(),500);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'name' => 'required|max:255',
            'filesize' => 'required|numeric|max:200',
            //'description' => 'sometimes|max:255',
            'img' => 'nullable|mimes:jpeg,png,bmp',
            'private' =>'nullable',
            //'amount'    =>'sometimes|max:10000',
            //'tags'      =>'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {

            //first create playlist
            $playlist = new Playlist;
            $playlist->playlist_title = $request->name;
            $playlist->user_id = Auth::id();
            if($request->private == true) $private = 1;
            else $private = 0;
            $playlist->private = $private;
            $playlist->playlist_type = 'collection';

            $playlist->save();

            if($request->file != ''){
                    $files = $request->file;

                    foreach ($files as $music_file) { //create new song instance for very file and stores in playlist

                        $song = new Song;
                        $song->user_id = Auth::guard('web')->id();

                        if($request->private == true) $song->upload_type = 'private';
                        else $song->upload_type = 'public';
                        $song->status = 'present';
                        $song->title = $music_file->getClientOriginalName();
                        $filename = 'playlist'.mt_rand().'time:'.time() . '.' . $music_file->getClientOriginalExtension();
                        $location = storage_path('app/public/songs');
                        $music_file->move($location,$filename);
                        $song->song_filename = $filename;
                        if ($request->hasFile('img')) { //image for everysongs in playlist selected
                            $image_file = $request->file('img');
                            $filename = 'playlist_pic' . time() . '.' . $image_file->getClientOriginalExtension();
                            $location = storage_path('app/public/images/songcovers');  // add artists id name anything here for folder structure.
                            $image_file->move($location,$filename);
                            
                            $song->image = $filename;
                        } 

                        //write for tags also same as image every song will have the tag selected for playlist

                        $song->save();

                        $playlist->songs()->sync($song->id, false);
                    }

            } else {
                
                return response()->json("no audio file");
            }

            //$song->song_description = $request->description;

            //$tags = json_decode($request->tags);
            //$tags_id = collect($tags)->pluck('id');
            //return $tags_id;
            //$song->tags()->sync($tags_id, false);

            return response()->json("Successfully uploaded your song. ");
        }
                

    }

    //removes song from playlist
    public function removeSong($s_id,$p_id)
    { 
        
        $song = Song::find($s_id);
        $song->playlists()->detach($p_id);
        /*
            $playlist = Playlist::find($p_id); works this way also since both modal has belongsTomany relation
            $playlist->songs()->detach($s_id);
        */
        //$song->delete();

        return 'success in removing song from playlist';


    }


    public function getPlaylist($user_id)
    {
        $playlists = Playlist::where('user_id', $user_id)->get();
        return $playlists;
    }

    public function deletedPlaylists()
    {
        //first just change status of playlist to delete....   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function show(Playlist $playlist)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Playlist $playlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update($playlist_id, Request $r)
    {
        $playlist = Playlist::find($playlist_id);
        $playlist->playlist_title = $r->playlist_title;
        $playlist->playlist_type = $r->playlist_type;
        if($r->private == true) $private = 1;
        else $private = 0;
        $playlist->private = $private;

        $check = Album::where('playlist_id', $playlist_id)->first();
        if(!empty($check)) {
            if($r->playlist_type == 'collection') {
                $album = Album::where('playlist_id', $playlist_id)->first();
                $album->delete();
            }
            $album = Album::where('playlist_id', $playlist_id)->first();
        } else {
            $album = new Album;
        }

        if($r->playlist_type == 'album') {
            $album->release_date = $r->release_date;
            //$album->playlist()->associate($playlist);
            $album->playlist_id = $playlist_id;
            $album->save();
        }

        $playlist->save();

        return $playlist;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy($playlist_id)
    {
        $playlist = Playlist::find($playlist_id);
        $playlist->songs()->detach();
        $playlist->delete();

        return 'deleted the playlist ' . $playlist->playlist_title;
    }
}
