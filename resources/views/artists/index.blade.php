@extends('main')
@section('title', '| All Categories')
@section('navbar_title', 'categories')

@section('content')
	<div class="row">
		<div class="col-md-8">
			<h1>catgeories</h1>
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($categories as $category)
					<tr>
						<th>{{ $category->id }}</th>
						<td><a href="{{ route('categories.show', $category->id) }}"> {{ $category->name }}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div><!-- end of col-md-8 -->
		<div class="col-md-3">
			<div class="well">
				<form method="POST" action="{{ route('categories.store') }}">
					{{ csrf_field() }}
					<h2>New category</h2>
					<label for="name">Name:</label>
					<input type="text" value="{{ old('name') }}" name="name" class="form-control">
					<input type="submit" value="add category" class="btn btn-primary btn-block btn-h1-spacing">
				</form>
			</div>
		</div>

	</div>
@endsection