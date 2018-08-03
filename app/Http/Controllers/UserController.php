<?php

namespace App\Http\Controllers;

use App\Artist;
use App\User;
use App\Profile;
use Session;
use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Notifications\EmailVerify;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status',null)->get(); 
        return view('admins.show_users')->withUsers($users);
    }

    public function deletedUsers()
    {
        $users = User::where('status', '!=' , 'null')->get(); 
        return view('admins.show_users')->withUsers($users);
    }

    public function profile($slug)
    {
        $user = User::where('slug', $slug)->first();
        return view('admins.show_user_profile')->withUser($user);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admins.add_user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender'    => 'required|boolean',
            'is_artist' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return redirect()->route('users.create')->withErrors($validator)->withInput();;
        }

        if($data['gender']) {
            $avatar = 'public/defaults/avatars/male.png';
        } else {
            $avatar = 'public/defaults/avatars/female.jpeg';
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'is_artist' => $data['is_artist'],
            'password' => bcrypt($data['password']),
            'slug' => str_slug($data['name']),
            'avatar' => $avatar,
            'token' => str_random(25)
        ]);
      
        Profile::create([
            'user_id' => $user->id
        ]);

        if(isset($data->verify)) {
            $user = User::where('token', $user->token)
                          ->update(['token' => null]);
            Session::flash('success','account has been added and verified. Now you can login with verified email');
            return redirect()->route('users.index');

        } else {
            $user->notify(new EmailVerify($user->token));
            Session::flash('success','please verify the account with associated email');

        }
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->first();
        $user->status = 'deleted';
        $user->save();
        //$user->delete();
        Session::flash('success','succesfully removed user from the system');
        return back();
    }

        /**
     * Show the form to change the user password.
     */
    public function changePass(){
        return view('artists.change_pass');
    }
 
    /**
     * Update the password for the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updatePass(Request $request)
    {
        $this->validate($request, [
            'old' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
 
        $user = User::find(Auth::id());
        $hashedPassword = $user->password;
 
        if (Hash::check($request->old, $hashedPassword)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
 
            $request->session()->flash('success', 'Your password has been changed.');
 
            return back();
        }
 
        $request->session()->flash('failure', 'Your password has not been changed.');
 
        return back();
 
 
    }
}
