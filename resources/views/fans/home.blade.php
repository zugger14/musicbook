@extends('main')
@section('title', 'Home')
@section('navbar_title', 'Music Book')

@section('content')
    <div class="row">
        <div class="col-md-12" >
            <div class="panel panel-default">
                <div class="panel-heading"> fan Dashboard</div>

                <div class="panel-body">
  only songs from following artists and reshared by other friends added..
                </div>
            </div>

            <song-feeds></song-feeds>

        </div>
    </div>
@endsection
