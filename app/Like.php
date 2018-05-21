<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $with = ['user'];

    protected $fillable = ['user_id','song_id'];

    public function song()
    {
    	return $this->belongsTo('App\Song');
    }

    public function user()
    {
        return $this->belongsTo('App\User');

    }

}
