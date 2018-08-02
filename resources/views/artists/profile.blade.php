@extends('main')
@section('title', 'profile')
@section('navbar_title', 'Music Book | Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        {{ $user->name }}'s Profile
                    </p>
                </div>

                <div class="panel-body">   
                    <div class="row">
                        <div class="col-md-5">
                            <change-profile-pic :auth="{{ Auth::guard('web')->id()}}" :profile_user_id="{{ $user->id }}" :avatar="{{ json_encode($user->avatar) }}" ></change-profile-pic>
                           {{--  need to json encode the avatar which contaons special characters like http://asd/.. other wise cannot send the props value to vuejs component  --}}
                            
                        </div>     
                        <div class="col-md-6">
                            <p><friend-button :profile_user_id="{{ $user->id }}"></friend-button></p>
                            <p><friends :user_id={{ $user->id }}></friends></p>
                        </div>
                    </div>                     
                    <p class="text-center">
                        @if(Auth::guard('web')->id() == $user->id)
                            <edit-profile :slug={{ json_encode($user->slug) }}></edit-profile>
                        @endif
                    </p>
                    <p>
                        UserName:   {{  $user->slug }}

                    </p>                    
                    <p>
                        FullName:   {{  $user->name }}

                    </p>                
                    <p>
                        Email:   {{  $user->email }}

                    </p>
                    <p>
                        Address:   {{  $user->profile->location }}

                    </p>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        About me
                    </p>
                </div>

                <div class="panel-body">
                    <p class="">
                        {{  $user->profile->about }}
                    </p>
                </div> 
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        Collections
                    </p>
                </div>

                <div class="panel-body">
                  <p class="">
                <a href="{{ route('artists.playlist', $user->id) }}" class="btn btn-default btn-md">Albums(playlists)</a>
                <a href="{{ route('artist.tracks')  }}" class="btn btn-default btn-md">Songs</a>
                <a href="{{ route('artist.notes', $user->id) }}" class="btn btn-default btn-md">Song Notes</a>
                <a href="{{ route('songs.liked', $user->id) }}" class="btn btn-default btn-md">Liked Songs</a>
                  tracks albums lyrics playlists are listed here...</p>
                </div> 
            </div>

        </div>
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        songs for sale
                    </p>
                </div>
                
                <div class="panel-body">
                    <demosong-view :tags="{{ $tags }}" :is_artist="{{ Auth::user()->is_artist }}" :artist_id="{{ $user->id }}"></demosong-view>
                    <p class="">
                        hello uppoaded songs goes here....
                    </p>
                </div> 
            </div>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        songs uploaded for free listen
                    </p>
                </div>

                <div class="panel-body">
                    <userpublicsong-view :tags="{{ $tags }}" :user_id={{ $user->id }}></userpublicsong-view>
                    <p class="">
                        hello uppoaded songs goes here....
                    </p>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection


