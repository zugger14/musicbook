@extends('main')
@section('title', '| Download Song')
@section('navbar_title', 'Download Song')
@section('content')
		this message should be session;;;Thank you for buying this song and helping out to the artists  {{ $payment['payer']['payer_info']['first_name'] }} so we have decided to give you download access to this file forever..

		{{ $song }}


	<song-download :song_id={{ $song }}></song-download>
    
@endsection 
