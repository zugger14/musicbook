@extends('main')
@section('title', '| Edit Tag')
@section('content')

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Edit Tags</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-9 col-sm-9 col-xs-9">
				<div class="x_panel">
					
					<div class="x_content">

						<form method="POST" action="{{ route('tags.update', $tag->id) }}">
							{{ csrf_field() }}
							{{ method_field('PUT') }}
							<h2>Edit tag</h2>
							<label for="name">Name:</label>
							<input type="text" value="{{ $tag->name }}" name="name" class="form-control">
							<input type="submit" value="save changes" class="btn btn-success btn-h1-spacing">
						</form>
					</div>
				</div>
			</div>
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
	</div>
</div>
@endsection

