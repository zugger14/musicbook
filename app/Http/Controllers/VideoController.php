<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use  SahusoftCom\YoutubeApi\AuthService;//for auth

use  SahusoftCom\YoutubeApi\YoutubeLiveEventService;//for live service


class VideoController extends Controller
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
        dd($response);
        if ( !empty($response) ) {

            $youtubeEventId = $response['broadcast_response']['id'];
            $serverUrl = $response['stream_response']['cdn']->ingestionInfo->ingestionAddress;
            $serverKey = $response['stream_response']['cdn']->ingestionInfo->streamName;
        }

            //dd($authToken);   
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
