@extends('main')
@section('title', '| Edit Category')
@section('content')
	<form method="POST" action="{{ route('categories.update', $category->id) }}">
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<h2>Edit Category</h2>
		<label for="name">Name:</label>
		<input type="text" value="{{ $category->name }}" name="name" class="form-control">
		<input type="submit" value="save changes" class="btn btn-success btn-h1-spacing">
	</form>
@endsection
