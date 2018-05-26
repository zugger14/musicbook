<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
	public $with = ['songs'];

    public function songs()
    {
        return $this->belongsToMany('App\Song')->withTimestamps();
    }

     public function user()
    {
        return $this->belongsTo('App\User');
    }


}