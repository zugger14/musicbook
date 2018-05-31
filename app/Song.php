<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Song extends Model
{   
    public $with = ['user','like','tags', 'share'];

	protected $hidden = [
         
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();

    }

    public function comments()
    {
        return $this->hasMany('App\Comment');

    }

    public function user()
    {
    	return $this->belongsTo('App\User');

    }

    public function like()
    {
        return $this->hasMany('App\Like');

    }

    public function share()
    {
        return $this->hasMany('App\Share');

    }


    public function getImageAttribute($image)//changes image attribute before acces from anythwere $user->avatar
    {
        return asset(Storage::url('public/images/songcovers/'.$image));
    }

    public function playlists()
    {
        return $this->belongsToMany('App\Playlist')->withTimestamps();

    }

    /*public function order()
    {
    	return $this->hasMany('App\Order');

    }
*/


    //
}
