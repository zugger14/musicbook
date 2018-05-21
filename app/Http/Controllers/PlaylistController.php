<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\Song;
use Illuminate\Http\Request;
use Validator;
use Auth;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $playlists = Playlist::where('user_id', $user_id)->get();
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
            if($request->private == true) $private = 1;
            else $private = 0;
            $playlist->private = $private;

            $playlist->save();

            return $playlist->id;
        }
    }

    //adds song to playlist 
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

    //removes song from playlist
    public function removeSong($s_id,$p_id)
    { 
        
        $song = Song::find($s_id);
        $song->playlists()->detach($p_id);
/*        $playlist = Playlist::find($p_id); works this way also since both modal has belongsTomany relation
        $playlist->songs()->detach($s_id);*/
        
        //$song->delete();

        return 'success in removing song to playlist';


    }


    public function getPlaylist($user_id)
    {
        $playlists = Playlist::where('user_id', $user_id)->get();
        return $playlists;
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
