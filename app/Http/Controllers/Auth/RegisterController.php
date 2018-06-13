<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\EmailVerify;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'artist/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('verify');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender'    => 'required|boolean',
            'is_artist' => 'required|boolean'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
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

        $user->notify(new EmailVerify($user->token));

        return $user;
    }

    public function verify($token)
    {
        $user = User::where('token', $token)
                      ->update(['token' => null]);
        Session::flash('success','Your account has been verified. Now you can login with verified email');

        return redirect()->route('landing');
    }
}
