@extends('main')
@section('title', '| All tags')
@section('navbar_title', 'tags')

@section('content')
	<div class="row">
		<div class="col-md-8">
			<h1>tags</h1>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($tags as $tag)
					<tr>
						<th>{{ $tag->id }}</th>
						<td><a href="{{ route('tags.show', $tag->id) }}"> {{ $tag->name }}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div><!-- end of col-md-8 -->
		<div class="col-md-3">
			<div class="well">
				<form method="POST" action="{{ route('tags.store') }}">
					{{ csrf_field() }}
					<h2>New tag</h2>
					<label for="name">Name:</label>
					<input type="text" value="{{ old('name') }}" name="name" class="form-control">
					<input type="submit" value="add tag" class="btn btn-primary btn-block btn-h1-spacing">
				</form>
			</div>
		</div>

	</div>
@endsection