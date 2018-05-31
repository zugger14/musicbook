@extends('main')
@section('title', 'Liked Songs')
@section('navbar_title', 'Music Book | Liked Songs')

@section('content')
	liked songs
    <div class="row">
        <liked-song :user_id={{ $user_id }}></liked-song>
    </div>

    shared songs
    <div class="row">
    	<shared-song :user_id={{ $user_id }}></shared-song>
    </div>
@endsection
