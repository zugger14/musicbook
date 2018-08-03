<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use App\Profile;
use Image;

class ProfileController extends Controller
{

    public function index($slug) 
    {
    	$user = User::where('slug', $slug)->first();

    	if($user->is_artist){
    		return view('artists.profile')->withUser($user);
    	} else {

    		return view('fans.profile')->withUser($user);
    	}

    }

    public function getProfile($slug) 
    {
        $user = User::where('slug', $slug)->first();
        return $user;

    }

    public function edit($slug)
    {
    	$user = Auth::user()->is_artist;
    	if($user) {
    		return view('artists.edit_profile')->withProfile(Auth::user()->profile)->withUser(Auth::user());
    	}
    		return view('fans.edit_profile')->withProfile(Auth::user()->profile)->withUser(Auth::user());

    }

    public function update(Request $r)
    {
    	$this->validate($r,[
    		'location' => 'required',
    		'about'	   => 'required|max:255',
            'name'     => 'required|max:255',
            'email'    => 'required|unique:users,email,'.$r->id,
            'slug'     => 'required|unique:users,slug,'.$r->id

    	]);

        $user = User::where('slug', $r->slug)->first();
        $user->update([
            'name' => $r->name,
            'email' => $r->email,
            'slug'  => $r->slug
        ]);

    	$user->profile()->update([
    		'location' => $r->location,
    		'about'    => $r->about
    	]);

    	if($r->hasFile('avatar')) {
            $file = $r->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $image = Image::make($file)->resize(400,400)->save(storage_path('app/public/avatars/') . $filename);
            Auth::user()->update([
                'avatar' => 'public/avatars/'.$filename
            ]);

    		/* without resizing also we can store using instance of upload file and creates unique name
                Auth::user()->update([
        			'avatar' => $r->avatar->store('public/avatars')
        		]);
            */    	
        }

        return response()->json('succesfully saved the profile info ', 200);

    	Session::flash('success', 'saved your profile successfully');
    	//$selecter = Auth::user()->is_artist ? 'artist' : 'fan';
    	return redirect()->route('profile.show', Auth::user()->slug);
    }

    public function updateProfilePic(Request $r)
    {

        if($r->hasFile('avatar')) {
            $file = $r->file('avatar');
            $filename = time(). '.' . $file->getClientOriginalExtension();
            $image = Image::make($file)->resize(400,400)->save(storage_path('app/public/avatars/').$filename);
            Auth::user()->update([//not working from admin side edit
                'avatar' => 'public/avatars/'.$filename
            ]);
        }
        return 'changed your profile picture';
    }

}
