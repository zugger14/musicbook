<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');

    }

    public function song()
    {
    	return $this->belongsTo('App\Song');

    }

    public function order()
    {
    	return $this->belongsTo('App\order');

    }
}
