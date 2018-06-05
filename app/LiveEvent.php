<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class LiveEvent extends Model
{
	protected $table = 'events';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getImageAttribute($image)
    {
        return asset(Storage::url('public/images/youtube-events/'.$image));

    }


}
