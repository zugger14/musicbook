@extends('main')
@section('title', "| $category->name Category")

@section('content')
<div class="row">
	<div class="col-md-8">
		<h2>{{ $category->name }}<div class="badge">{{ $category->posts()->count() }}</div></h2>
	</div>
	<div class="col-md-2">
		<a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-block pull-right btn-top-spacing">edit</a>
	</div>
	<div class="col-md-2">
		<form method="post" action="{{ route('categories.destroy', $category->id) }}">
			{{ csrf_field() }}
			{{ method_field('delete') }}
			<button type="submit" class="btn btn-danger btn-block pull-right btn-top-spacing">delete</button>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>title</th>
					<th>category</th>
					<th>post view</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($category->posts as $post)
				<tr>
					<th>{{ $post->id }}</th>
					<td>{{ $post->title }}</td>
					<td>{{ $category->name }}</td>
					<td><a href="{{ route('posts.show', $post->id)}}" class="btn btn-default btn-sm">view</a></td>
				</tr>
				@endforeach
			</tbody>
			
		</table>
	</div>
</div>

@endsection
