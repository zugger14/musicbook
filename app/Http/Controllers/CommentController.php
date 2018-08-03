<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\CommentSong;
use App\Comment;
use App\Song;
use Auth;
use App\User;
use Validator;

class CommentController extends Controller
{
    /**
     * get comments for a song by song_id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($song_id)
    {
        $comments = Comment::where('song_id', $song_id)->get();
        return $comments;
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
            'song_id' => 'required|max:255',
            'comment' => 'required|min:5|max:2000'

        ]);

		if($validator->fails()){
            return response()->json($validator->errors(),500);
        }

        $song = song::find($request->song_id);

        $user = User::find($song->user_id);

        if($user->id != Auth::id()) {
            $user->notify(new CommentSong(Auth::guard('web')->user(), $song));
        }
        
        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->comment = $request->comment;
        $comment->status = 'allowed';
        $comment->song()->associate($song);

        $comment->save();

        $comment = Comment::find($comment->id);

        return $comment;


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        $validator = Validator::make($request->all(), [
            'song_id' => 'required|max:255',
            'comment' => 'required|min:5|max:2000'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);
        }

        $comment->comment = $request->comment;
        $comment->save();

        return $comment->id;
    }

    public function delete($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return $comment->id;

  	}
}
