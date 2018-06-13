<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{	
    protected $fillable = ['followed_id','follower_id'];

   	public $with = ['follower','followed'];

   	public function follower()
    {
    	return $this->belongsTo('App\User','follower_id','id');
    }

    public function followed()
    {
    	return $this->belongsTo('App\User','followed_id','id');
    }


}
