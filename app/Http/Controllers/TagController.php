<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Tag;
use App\Song;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('tags.index')->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array('name' => 'required|max:255'));
        $tag = new Tag;
        $tag->name = $request->name;

        $tag->save();

        Session::flash('success', 'New tag has been added');

        return redirect()->route('tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        return view('tags.show')->withTag($tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        return view('tags.edit')->withTag($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $tag = Tag::find($id);
        $this->validate($request, [
            'name'  => 'required|max:255'
        ]);

        $tag->name = $request->name;
        $tag->save(); 

        Session::flash('success', 'The Tag has been succesfully modified.');

        return redirect()->route('tags.show' , $tag->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        $tag->songs()->detach();
        $tag->delete();

        Session::flash('success', 'The Tag has been succesfully deleted ');

        return redirect()->route('tags.index');
    }

    public function getTagIds(Request $song) //get id of tag by song 
    { 
        $song_id = $song->id;
        $song = Song::find($song_id);
        $ids = $song->tags()->allRelatedIds();
        $tags = Tag::all('id','name')->whereIn('id', $ids);//pluck returns array so all('','')

        $taged = array();
        foreach ($tags as $tag) {
            array_push($taged, $tag);//if collection sent js will get nested object so for v-select i need array of objects or i should have used get() above with select instead of all.
           // $tag->label = $tag->name;
        }

        //$tags = Tag::all('id','label')->whereIn('id', $ids);
        //$tags = collect($tags)->pluck('id');//only one sigle column but i need name for label also so dont use 
        return $taged;

    }
}
