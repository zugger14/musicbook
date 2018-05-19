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
                        Songs uploaded
                    </p>
                </div>

                <div class="panel-body">
                    <publicsong-view :user_id={{ $user->id }}></publicsong-view>
                    <p class="">
                        hello shared songs goes here....

                    </p>
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
