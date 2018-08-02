@extends('main')
@section('title', 'Collections')
@section('navbar_title', 'Collections')

@section('content')
    <div class="row">
        <div class="col-md-12" >
    		<collections-sidebar :user_id="{{ $user_id }}" :tags="{{ $tags }}"></collections-sidebar>
        </div>
    </div>
@endsection
