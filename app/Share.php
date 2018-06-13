<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{	

	public $with = ['user'];
	protected $fillable = ['user_id', 'song_id'];

    public function song()
    {
    	return $this->belongsTo('App\Song')->orderBy('created_at', 'Desc');
    }

    public function user()
    {
        return $this->belongsTo('App\User');

    }
}
