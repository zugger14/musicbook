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
                            {{-- <img src="{{ $user->avatar }}" width="140px" height="140px" style="border-radius: 50%;">  --}}
                            <change-profile-pic :avatar={{ json_encode($user->avatar) }} ></change-profile-pic>
                        </div>     
                        <div class="col-md-5">
                            <p><friend-button :profile_user_id="{{ $user->id }}"></friend-button></p>

                            <p><friends :user_id={{ $user->id }}></friends></p>
                                                        
                        </div>
                    </div>                     
                    @if(Auth::guard('web')->id() == $user->id)
                        <p class="text-center">
                            <edit-profile :slug={{ json_encode($user->slug) }}></edit-profile>
                        </p>
                    @endif
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
                      
                </p>
              </div> 
          </div>

        </div>


        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        songs uploaded for free listen
                    </p>
                </div>

                <div class="panel-body">
                    <userpublicsong-view :tags="{{ $tags }}" :user_id={{ $user->id }}></userpublicsong-view>
=                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
