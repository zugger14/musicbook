<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Traits\Friendable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Storage;

class User extends Authenticatable
{
    use Notifiable;
    use Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $with=['profile'];

    protected $fillable = [
        'name', 'email', 'password','slug','gender','avatar','is_artist'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /*  public function orders()
    {
        return $this->hasMany('App\Order');
    }
    */
    public function songs()
    {
        return $this->hasMany('App\Song');
    }

    public function like()
    {
        return $this->hasMany('App\Like');

    }

    public function share()
    {
        return $this->hasMany('App\Share');

    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }



    public function playlists()
    {
        return $this->hasMany('App\Playlist');
    }


    public function getAvatarAttribute($avatar)//changes avatar attribute before acces from anythwere $user->avatar
    {
        return asset(Storage::url($avatar));
    }


}
