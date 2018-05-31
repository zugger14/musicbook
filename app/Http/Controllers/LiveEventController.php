<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  SahusoftCom\YoutubeApi\AuthService;//for auth
use  SahusoftCom\YoutubeApi\YoutubeLiveEventService;//for live service
use App\Video;
use Log;


class LiveEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $authServiceObject = new AuthService();
            $authUrl = $authServiceObject->getLoginUrl('delonge.shirish@gmail.com','UC2Y_9je1_rLFahkNuyXOHGA');
            //dd($authUrl);
            return redirect()->to($authUrl);
    }

    public function getToken()
    {       
            $authServiceObject = new AuthService();
            $code = request()->get('code');
            $identifier = request()->get('state');
            $authToken = $authServiceObject->getToken($code);

            $data = array(
            "title" => "new",
            "description" => "i dont know what is this",
            "thumbnail_path" => "",             // Optional
            "event_start_date_time" => date("Y-m-d H:i:s"),
            "event_end_date_time" => "",            // Optional
            "time_zone" => "UTC",
            'privacy_status' => "public",             // default: "public" OR "private"
            "language_name" => "",              // default: "English"
            "tag_array" => []               // Optional and should not be more than 500 characters
        );

        $ytEventObj = new YoutubeLiveEventService();
        /**
         * The broadcast function returns array of details from YouTube.
         * Store this information & will be required to supply to youtube 
         * for live streaming using encoder of your choice. 
         */
        $response = $ytEventObj->broadcast($authToken, $data);
       // dd($response);
        if ( !empty($response) ) {

            $youtubeEventId = $response['broadcast_response']['id'];
            $serverUrl = $response['stream_response']['cdn']->ingestionInfo->ingestionAddress;
            $serverKey = $response['stream_response']['cdn']->ingestionInfo->streamName;

            $server_info = array('server_url ' => $serverUrl, 'server_name' => $serverKey);
            Log::info($serverKey);
            //dd($serverKey);
        }

        //show $server_info to users for streaming and setup for encoders

    }

    public function createLiveEvent()
    {

        $data = array(
            "title" => "new",
            "description" => "hello this is working great",
            "thumbnail_path" => "",             // Optional
            "event_start_date_time" => date(),
            "event_end_date_time" => "",            // Optional
            "time_zone" => config('app.timezone'),
            'privacy_status' => "public",             // default: "public" OR "private"
            "language_name" => "",              // default: "English"
            "tag_array" => ""               // Optional and should not be more than 500 characters
        );

        $ytEventObj = new YoutubeLiveEventService();
        /**
         * The broadcast function returns array of details from YouTube.
         * Store this information & will be required to supply to youtube 
         * for live streaming using encoder of your choice. 
         */

        $response = $ytEventObj->broadcast($authToken, $data);
       
        if ( !empty($response) ) {

            $youtubeEventId = $response['broadcast_response']['id'];
            $serverUrl = $response['stream_response']['cdn']->ingestionInfo->ingestionAddress;
            $serverKey = $response['stream_response']['cdn']->ingestionInfo->streamName;
        }

    }

    public function startEventLiveStream($authToken, $youtubeEventId)
    {   
        // i think it is btetter to get above parameters from db,because users dont have to start immediatle after making a event so the page will not have any variables stored for future steaming so store the credentials for stting team in db with artists info and use for startinf the stream whenever he artist has intended to do.
         $broadcastStatus = ["testing"];
        /**
         * $broadcastStatus - ["testing", "live"]
         * Starting the event takes place in 3 steps
         * 1. Start sending the stream to the server_url via server_key recieved as a response in creating the event via the encoder of your choice.
         * 2. Once stream sending has started, stream test should be done by passing $broadcastStatus="testing" & it will return response for stream status.
         * 3. If transitioEvent() returns successfull for testing broadcast status, then start live streaming your video by passing $broadcastStatus="live" 
         * & in response it will return us the stream status.
         */ 
        $streamStatus = $ytEventObj->transitionEvent($authToken, $youtubeEventId, $broadcastStatus);

        dd($streamStatus);
                    //dd($authToken);   
        
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
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
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }
}
