@extends('main')
@section('title', 'Home')
@section('navbar_title', 'Music Book')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1" >
            <div class="panel panel-default">
                <div class="panel-heading"> Notifications </div>

                <div class="panel-body">
                    @foreach($notifications as $notification)
                    <div class="alert alert-success notification-item">
                        {{ $notification->data['name'] }}
                        
                        @if(isset($notification->data['message'] )) 
                         {{ $notification->data['message'] }}
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
