@extends('main')
@section('title', 'Songs')
@section('navbar_title', 'Music Book | Songs')


@section('content')
    <div class="row">
        <div class="col-md-6" >
            <userpublicsong-view :tags="{{ $tags }}" :user_id={{ Auth::id() }}></userpublicsong-view>
        </div>    
        <div class="col-md-6" >
            <div class="panel panel-default">
                <div class="panel-heading"> For Sale Songs</div>
                <demosong-view :tags="{{ $tags }}" :artist_id="{{ Auth::id() }}" :is_artist={{ Auth::user()->is_artist }}></demosong-view>
                <div class="panel-body">

                </div>
            </div>
        </div>
        </div>
    @endsection
