<div class="col-md-6 col-md-offset-3">
	
@if (Session::has('success'))
	<div class="alert alert-success" role="alert">
		<strong>
			Success:{{ Session::get('success') }}
		</strong>
	</div>

@endif
	
@if (Session::has('message'))
		<div class="alert alert-info" role="alert">
		<strong>
			{{ Session::get('message') }}
		</strong>
	</div>

@endif

@if (count($errors) > 0)
<div class="alert alert-danger" style="z-index:99999;">
	<strong>Errors:</strong>
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
</div>