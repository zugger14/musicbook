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
                            <img src="{{ $user->avatar }}" width="140px" height="140px" style="border-radius: 50%;"> 
                        </div>     
                        <div class="col-md-5">
                            <p><friend :profile_user_id="{{ $user->id }}"></friend></p>

                            <p>Friends: {{ count(Auth::user()->friends()) }}</p>
                            
                        </div>
                    </div>                     
                    @if(Auth::guard('web')->id() == $user->id)
                        <p><a href="" class="btn btn-default btn-md">change profile picture </a></p>
                        <p class="text-center">
                            <a class="btn btn-default btn-md" href="{{ route('profile.edit', $user->slug) }}">Edit profile</a>
                        </p>
                    @endif
                    <p>
                        Profile url:   {{  $user->profile->location }}

                    </p>                    
                    <p>
                        FirstName:   {{  $user->profile->location }}

                    </p>                
                    <p>
                        LastName:   {{  $user->profile->location }}

                    </p>
                    <p>
                        Address:   {{  $user->profile->location }}

                    </p>
                    <p>

                        phone:

                    </p>
                    <p>

                        email:

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
                  tracks albums liked songs by users lyrics playlists are listed here...</p>
              </div> 
          </div>

        </div>

        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="">
                        Songs shared
                    </p>
                </div>

                <div class="panel-body">
                    <p class="">
                        hello shared songs goes here....

                    </p>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
