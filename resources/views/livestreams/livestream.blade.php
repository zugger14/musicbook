@extends('main')
@section('title', 'Live')
@section('navbar_title', 'Music Book | LiveStream')


@section('content')
    <div class="row">
        <div class="col-md-12" >
            <div class="panel panel-default">
                <div class="panel-heading"> Live</div>

                <div class="panel-body">
                    <div class="row">
                        
                    <iframe class="pull-left" width="800" height="400" src="https://www.youtube.com/embed/{{ $event_id }}" frameborder="0" allowfullscreen></iframe>
                    <iframe class="pull-right" allowfullscreen="" frameborder="0" height="400" src="https://www.youtube.com/live_chat?v={{$event_id}}&embed_domain=localhost" width="500"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
