@extends('main')
@section('title', 'Users')
@section('navbar_title', 'Music Book')

@section('content')

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>User profile </h3>
			</div>
			<div class="title_right">
				<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
{{--                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Go!</button>
                        </span>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
        	<div class="col-md-12 col-sm-12 col-xs-12">
        		<div class="x_panel">

        			<div class="x_content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <p class="">
                                                {{ $user->name }}'s Profile
                                            </p>

                                            <label> <a href="{{ route('users.destroy', $user->slug) }}" onClick="return confirm('Are you absolutely sure you want to delete?')"><i class="fa fa-trash"></i>Delete/Ban user</a> </label>
                                        </div>

                                        <div class="panel-body">   
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <change-profile-pic :auth="{{ Auth::guard('admin')->id() }}" :profile_user_id="{{ $user->id }}" :avatar="{{ json_encode($user->avatar) }}" ></change-profile-pic>
                                                    {{--  need to json encode the avatar which contaons special characters like http://asd/.. other wise cannot send the props value to vuejs component  --}}

                                                </div> 
                                            </div>                     
                                            <p class="text-center">
                                                @if(Auth::guard('admin')->check())
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
                                        </p>
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
                                        <demosong-view :tags="{{ $tags }}" :is_artist="false" :artist_id="{{ $user->id }}"></demosong-view>
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
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('admin-template/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('admin-template/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('admin-template/build/js/custom.min.js') }}"></script> {{-- needs this js otherwisse all li links are unclickable --}}
@endsection


