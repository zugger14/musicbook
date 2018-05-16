<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{   
    public $with = ['user','like'];

	protected $hidden = [
         
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');

    }

    public function like()
    {
        return $this->hasMany('App\Like');

    }


    /*public function order()
    {
    	return $this->hasMany('App\Order');

    }
*/


    //
}
