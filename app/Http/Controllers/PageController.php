<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;
use App\user;
use Auth;
use App\Message;
use DB;
use Validator;
use App\Events\MessagePosted;

class PageController extends Controller
{

	public function __construct()
	{
		$this->middleware('guest')->only('index');
	}

	public function index()
	{		
		
		return view('guests.welcome');

	}

	public function artistHome()
    {       
        return view('artists.home');

    }

     public function collection()
    {       
        return view('artists.home');

    }

    public function fanHome()
	{		
		return view('fans.home');
	}

    

	public function about()
	{
		return view('pages.about');

	}

	public function contact()
	{
		return view('pages.contact');
	}

	public function showAllNotifications()
	{
		return view('notifications.index')->withNotifications(Auth::guard('web')->user()->readnotifications);
	}

	public function emailContact(Request $request)
	{
		$this->validate($request, [
			'email'		=> 'required|max:255',
			'subject'	=> 'required|min:5|max:255',
			'message'	=> 'required|min:3'
		]);

		$data = [
			'email'=>$request->email,
			'subject'=>$request->subject,
			'bodymessage'=>$request->message
		];

		Mail::send('emails.contact', $data, function ($message) use($data) {
			$message->from($data['email']);
			$message->to('shirish.maharjan@apexcollege.edu.np');
			$message->subject($data['subject']);
		});

		Session::flash('success', 'you email was sent succesfully ');
		return redirect('/');
		
	}

	public function getMessage(Request $request)
	{
		$users = User::all()->where('user_type', '!=', 'admin')->except(Auth::id());	//all users from brand and influencer to direct messages

		$admins = User::all()->where('user_type', '=', 'admin')->except(Auth::id());	//all addmins

        $messages = DB::table('messages')->join('users', 'users.id', '=', 'messages.sender_id')->where('sender_id', '=', Auth::id())->orWhere('receiver_id', '=', Auth::id())->orderBy('messages.id', 'asc')->get();					//messages data sent by admin and receiver data of users table

       // dd($messages);

		return view('pages.message')->withUsers($users)->withAdmins($admins)->withMessages($messages);
	}

	public function postMessage(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'message_text' => 'required|max:255',
            'receiver_id'	=>'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
		} else {

	    	$message = new Message;

	    	$message->sender_id = Auth::id();
	    	$message->receiver_id = $request->receiver_id;
	    	$message->message_text = $request->message_text;

	    	$message->save();

	    	$message = "Your message has been sent successfully";
            return response()->json($message);

		}

	}

	public function chat()
	{
		return view('pages.chat');
	}

	public function getChatMessage(Request $request)
	{
		$messages = Message::with('user')->get();
		return $messages;
	}

	public function storeChatMessage(Request $request)
	{		
			$user = Auth::user();
	    	$message = new Message;
	    	$message->sender_id = Auth::id();
	    	$message->receiver_id = Auth::id();
	    	$message->message_text = $request->message_text;
	    	$message->save();

	    	broadcast(new MessagePosted($message, $user))->toOthers();
	    	$message = "Your message has been sent successfully";
           	return response()->json($message);
		
	}

    
}
