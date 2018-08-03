<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Purifier;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        return view('artists.notes')->with('user_id', $user_id);
    }

    /*
        for admin side all notes listing
    */
    public function allNotes()
    {
        $notes = Note::all();
        return view('admins.show_notes')->withNotes($notes);
    }
    public function getNotes($user_id)
    {
        $notes = Note::where('user_id', $user_id)->where('private', 0)->get();
        return $notes;
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
            'title' => 'required|max:255',
            'content' => 'required|max:2000',
            'private' => 'nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {    
            $note = new Note;
            $note->title = $request->title;
            $note->content = Purifier::clean($request->content);
            if($request->private == true ){
                $note->private = 1;
            }
            $note->private = 0;
            $note->user_id = Auth::id();   
            $note->save();

            return $note->id;

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($note_id)
    {
        $note = Note::find($note_id);
        return $note;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $Note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $Note
     * @return \Illuminate\Http\Response
     */
    public function update($note_id, Request $r)
    {
        $validator = Validator::make($r->all(), [
            'title' => 'required|max:255',
            'content' => 'required|max:2000',
            'private' => 'boolean',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),500);

        } else {   

            $note = Note::find($note_id);
            $note->title = $r->title;
            $note->content = Purifier::clean($r->content);
            $note->user_id = Auth::id();     
            if($r->private == true ){
                $note->private = 1;
            }
            $note->private = 0;
            $note->save();

            return $note->id;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $Note
     * @return \Illuminate\Http\Response
     */
    public function destroy($note_id)
    {
        $note = Note::find($note_id);
        $note->delete();

        return $note->id;
    }
}
