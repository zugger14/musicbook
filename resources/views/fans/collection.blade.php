@extends('main')
@section('title', 'Collections')
@section('navbar_title', 'Collections')

@section('content')
    <div class="row">
        <div class="col-md-12" >
            <purchasedsong-view :tags="{{ $tags }}"> </purchasedsong-view>
        </div>
    </div>
@endsection
