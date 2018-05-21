@extends('main')
@section('title', 'Tracks')
@section('navbar_title', 'Music Book | Tracks')

@section('content')
    <div class="row">
        <div class="col-md-6" >
            <div class="panel panel-default">
                <div class="panel-heading"> Free Tracks</div>

                <div class="panel-body">
                    
                    <userpublicsong-view :user_id={{ Auth::id() }}></userpublicsong-view>

                </div>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="panel panel-default">
                <div class="panel-heading"> For Sale Tracks</div>
                    <demosong-view :artist_id="{{ Auth::id() }}" :is_artist={{ Auth::user()->is_artist }}></demosong-view>
                <div class="panel-body">
                    

                </div>
            </div>
        </div>
    </div>
@endsection
