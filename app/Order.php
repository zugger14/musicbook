<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');

    }

    public function song()
    {
    	return $this->belongsTo('App\Song');

    }

/*
for albums also similar relation
    public function songs()
    {
    	return $this->belongsTo('App\Song');

    }
*/
}
