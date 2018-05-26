@extends('main')
@section('title', 'Song Notes')
@section('navbar_title', 'Music Book | Song Notes')

@section('content')
    <div class="row">
        {{-- add date of note stored  and show also --}}
        <add-note :user_id={{ $user_id }}></add-note>
    </div>
@endsection
