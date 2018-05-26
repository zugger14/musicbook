<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{	
    public function songs()
    {
    	return $this->belongsToMany('App\Song')->withTimestamps();
    }

}
