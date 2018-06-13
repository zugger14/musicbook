@extends('main')
@section('title', 'Home')
@section('navbar_title', 'Music Book')

@section('content')
    <div class="row">
        <div class="col-md-12" >
{{--             <div class="panel panel-default">
                <div class="panel-heading"> Artist Dashboard</div>

                <div class="panel-body">
                    

                </div>
            </div>
 --}}            <song-feeds :is_artist="{{ Auth::user()->is_artist }}"></song-feeds>
        </div>
    </div>
@endsection
