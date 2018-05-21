@extends('main')
@section('title', 'Playlists')
@section('navbar_title', 'Music Book | Playlists')

@section('content')
    <div class="row">
        <show-playlist :user_id={{ $user_id }}></show-playlist>
        {{-- <div class="col-md-8" >
                @foreach($playlists as $playlist)
            <div class="panel panel-default">
                    <div class="panel-heading"> {{ $playlist->playlist_title }} </div>
                    @foreach($playlist->songs as $song)
                    <div class="panel-body">
                        {{ $song->title }}
                        {{ $song->image }}
                    </div>
                    @endforeach
            </div>
                @endforeach
        </div> --}}
    </div>
@endsection
