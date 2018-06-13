@extends('main')
@section('title', 'Live Events')
@section('navbar_title', 'Live Events')

@section('content')
    <div class="row">
        <div class="col-md-12" >
            <div class="panel panel-default">
                <div class="panel-heading">  Create a Live Event </div>

                <div class="panel-body">
                    @if(isset($token))
                        You are authenticated for createing a live events
                        <create-live-event :auth_token="{{ json_encode($token) }}"> </create-live-event>
                    @else
                        you must login woth your youtube account first.please press below button and follow authentication procedure
                        <a href="{{ route('event.login') }}" class="btn btn-md btn-default">Login to youtube account</a>
                    @endif
                </div>
            </div>
            @if(isset($token))

                <live-events :user_id="{{ Auth::id() }}"></live-events>
            @endif
        </div>
    </div>
@endsection
