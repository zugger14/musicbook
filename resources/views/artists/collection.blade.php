@extends('main')
@section('title', 'Collections')
@section('navbar_title', 'Collections')

@section('content')
    <collections-sidebar :user_id="{{ $user_id }}" :tags="{{ $tags }}"></collections-sidebar>
@endsection
<style scoped>
	[v-cloak] {
      display: none;
    }

</style>
