@extends('main')
@section('title', '| Edit Tag')
@section('content')
	<form method="POST" action="{{ route('tags.update', $tag->id) }}">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<h2>Edit tag</h2>
		<label for="name">Name:</label>
		<input type="text" value="{{ $tag->name }}" name="name" class="form-control">
		<input type="submit" value="save changes" class="btn btn-success btn-h1-spacing">
	</form>
@endsection
