<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\user;
use Carbon\Carbon;

class PrivateMessage extends Model
{
    protected $fillable = [
    	'sender_id','receiver_id','subject','read','message'
    ];

    protected $appends = ['sender', 'receiver'];

    public function getCreatedAtAttribute($value)
    {
    	return Carbon::parse($value)->diffForHumans();
    }

    public function getSenderAttribute()
    {
    	return User::where('id', $this->sender_id)->first(); 
    }

     public function getReceiverAttribute()
    {
    	return User::where('id', $this->receiver_id)->first(); 
    }

}
