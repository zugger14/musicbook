@extends('main')
@section('title', 'Home')
@section('navbar_title', 'Music Book')

@section('content')
    <div class="row">
        <div class="col-md-12" >
            <song-feeds :is_artist="{{ Auth::user()->is_artist }}"></song-feeds>
        </div>
    </div>
@endsection
