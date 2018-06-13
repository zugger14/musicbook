<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Album;

class Playlist extends Model
{
	public $with = ['songs' , 'album'];
    //protected $appends = ['album'];

    public function songs()
    {
        return $this->belongsToMany('App\Song')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function album()
    {
        return $this->hasOne('App\Album', 'playlist_id', 'id' );
    }


    /*    public function getAlbumAttribute()
        {   
            return Album::where('playlist_id', $this->id)->first();
        }
    */


    public function getPrivateAttribute($private)
    {
        if($private == 1) return true;
        return false;
    }   

}
