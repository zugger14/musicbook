<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = ['requester', 'user_requested', 'status'];


   	public function requester()
    {
    	return $this->belongsTo('App\User','requester_id','id');
    }   	

    public function requested()
    {
    	return $this->belongsTo('App\User','user_requested','id');
    }

}
