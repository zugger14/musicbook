@extends('main')
@section('title', '| Private Message')
@section('content')
	<pm-sidebar :user_id={{ Auth::id() }}></pm-sidebar>
@endsection
