@extends('main')
@section('title', 'Liked Songs')
@section('navbar_title', 'Music Book | Liked Songs')

@section('content')
    <div class="row">
        <liked-song :user_id={{ $user_id }}></liked-song>
    </div>
@endsection
