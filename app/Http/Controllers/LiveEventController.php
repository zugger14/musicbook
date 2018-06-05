<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  SahusoftCom\YoutubeApi\AuthService;//for auth
use  SahusoftCom\YoutubeApi\YoutubeLiveEventService;//for live service
use App\LiveEvent;
use Log;
use Auth;


class LiveEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $event = LiveEvent::where('user_id', Auth::id())->first();

        if(!empty($event)) {
            $authToken = json_decode($event->auth_token,true);

            return view('artists.live_event')->withToken($authToken);
        } else {
            return view('artists.live_event');
        }

    }

    public function live($user_id)
    {
        $event = LiveEvent::where('user_id',$user_id)->first();
        $token = json_decode($event->auth_token, true);

        $ytEventObj = new YoutubeLiveEventService();
        $event = $ytEventObj->getActiveEvent($token);

        if(!empty($event)) {
            return view('livestreams.livestream')->with('event_id', $event->id);
        }
        return 'no any live session from this user';

    }

    public function getEvents()
    {
        $event = LiveEvent::where('user_id', Auth::id())->first();
        $token = json_decode($event->auth_token, true);

        $ytEventObj = new YoutubeLiveEventService();
        $events = $ytEventObj->getEvents($token);

        return $events;

    }

    public function getEventById($event_id)
    {   
        $event =  LiveEvent::where('youtube_event_id', $event_id)->first();
        return $event;
    }

    public function login()
    {
            $authServiceObject = new AuthService();
            $authUrl = $authServiceObject->getLoginUrl('','');
            return redirect()->to($authUrl);

    }

    public function getToken()
    {       
            $authServiceObject = new AuthService();
            $code = request()->get('code');
            $identifier = request()->get('state');
            $authToken = $authServiceObject->getToken($code);

/*            $events = LiveEvent::where('user_id', Auth::id())->get();
            foreach ($events as $event) {
                $event->auth_token = json_encode($authToken);//update auth token in database after new login auth
                $event->save();
            }*/
            
            return view('artists.live_event')->withToken($authToken);
    }

    public function createLiveEvent(Request $r)
    {   
        if($r->privacy_status == 'true') {
            $privacy_status = 'private';
        } else {
            $privacy_status = 'public';
        }

        if ($r->hasFile('image')) {
            $image_file = $r->file('image');
            $filename = time() . '.' . $image_file->getClientOriginalExtension();
            $location = storage_path('app/public/images/youtube-events/');  // add artists id name anything here for folder structure.
            $image_file->move($location,$filename);
            $thumbnail_path = $location . $filename;
            
            $r->thumbnail_path = $filename;
           // $r->request->add(['thumbnail_path'=>$filename]);add or set(key,value)
        } 

        $data = array(
            "title" => $r->title,
            "description" => $r->description,
            "thumbnail_path" => isset($thumbnail_path) ? $thumbnail_path : '',             // Optional
            "event_start_date_time" => date("Y-m-d H:i:s"),
            "event_end_date_time" => "",            // Optional
            "time_zone" => config('app.timezone'),
            'privacy_status' => $privacy_status,    // default: "public" OR "private"
            "language_name" => "",              // default: "English
            "tag_array" => []               // Optional and should not be more than 500 characters
        );
        $token = json_decode($r->auth_token, true);

        $ytEventObj = new YoutubeLiveEventService();
        /**
         * The broadcast function returns array of details from YouTube.
         * Store this information & will be required to supply to youtube 
         * for live streaming using encoder of your choice. 
         */

        $response = $ytEventObj->broadcast($token, $data);
       
        if ( !empty($response) ) {

            $youtubeEventId = $response['broadcast_response']['id'];
            $serverUrl = $response['stream_response']['cdn']->ingestionInfo->ingestionAddress;
            $serverKey = $response['stream_response']['cdn']->ingestionInfo->streamName;
            $r->youtubeEventId = $youtubeEventId;
            $r->streamUrl = $serverUrl;
            $r->streamKey = $serverKey;

        }

        $stored_event = $this->store($r);

        return response()->json($stored_event);

    }

    public function testEventLiveStream($event_id)
    {
        $ytEventObj = new YoutubeLiveEventService();
        $broadcastStatus = ["testing"];
        /**
         * $broadcastStatus - ["testing", "live"]
         * Starting the event takes place in 3 steps
         * 1. Start sending the stream to the server_url via server_key recieved as a response in creating the event via the encoder of your choice.
         * 2. Once stream sending has started, stream test should be done by passing $broadcastStatus="testing" & it will return response for stream status.
         * 3. If transitioEvent() returns successfull for testing broadcast status, then start live streaming your video by passing $broadcastStatus="live" 
         * & in response it will return us the stream status.
         */

        $event = LiveEvent::where('id', $event_id)->where('user_id', Auth::id())->first();

        $authToken = json_decode($event->auth_token, true);
        $youtubeEventId = $event->youtube_event_id;
        $streamStatus = $ytEventObj->transitionEvent($authToken, $youtubeEventId, $broadcastStatus);
        return response()->json($streamStatus);

    }

    public function startEventLiveStream($event_id)
    {   
        $ytEventObj = new YoutubeLiveEventService();

        $broadcastStatus = ["live"];

        $event = LiveEvent::where('id', $event_id)->where('user_id', Auth::id())->first();

        $authToken = json_decode($event->auth_token, true);

        $youtubeEventId = $event->youtube_event_id;

        $streamStatus = $ytEventObj->transitionEvent($authToken, $youtubeEventId, $broadcastStatus);

        $event->status = 'active';
        $event->save();

        return response()->json($streamStatus);

        
    }

    public function stopEventLiveStream($event_id)
    {   
        $ytEventObj = new YoutubeLiveEventService();
        $broadcastStatus = ["complete"];
        /**
         * $broadcastStatus - ["complete"]
         * Once live streaming gets started succesfully. We can stop the streaming the video by passing broadcastStatus="complete" and in response it will give us the stream status.
         */
        $event = LiveEvent::where('id', $event_id)->where('user_id', Auth::id())->first();

        $authToken = json_decode($event->auth_token, true);
        $youtubeEventId = $event->youtube_event_id;
        $ytEventObj->transitionEvent($authToken, $youtubeEventId, $broadcastStatus); // $broadcastStatus = ["complete"]

        $event->status = 'completed';
        $event->save();

        return response()->json('succesfully stoped!');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($r)
    {
        $event = new LiveEvent;
        $event->title = $r->title;
        $event->description = $r->description;
        $event->user_id = Auth::id();
        $event->status = 'upcoming';
        $event->image = $r->thumbnail_path;
        $event->private = $r->privacy_status == 'true' ? 1 : 0;
        $event->auth_token = $r->auth_token;
        $event->youtube_event_id = $r->youtubeEventId;
        $event->stream_url = $r->streamUrl;
        $event->stream_key = $r->streamKey;


        $event->save();

        return $event;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */

    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
