@extends('main')
@section('title', "| $tag->name Tag")

@section('content')
	<div class="row">
		<div class="col-md-8">
			<h2>{{ $tag->name }}<div class="badge">{{ $tag->songs()->count() }}</div></h2>
		</div>
		<div class="col-md-2">
			<a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-primary btn-block pull-right btn-top-spacing">edit</a>
		</div>
		<div class="col-md-2">
			<form method="post" action="{{ route('tags.destroy', $tag->id) }}">
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
				<th>tags</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($tag->songs as $song)
			<tr>
				<th>{{ $song->id }}</th>
				<td>{{ $song->title}}</td>
				<td>
					@foreach ($song->tags as $tag)
						<span class="label label-info">{{ $tag->name }}</span>
					@endforeach
				</td>
				<td><a href="{{ route('songs.show', $song->id)}}" class="btn btn-default btn-sm">view</a></td>
			</tr>
		@endforeach
		</tbody>
			
		</table>
	</div>
</div>

@endsection
