@if (Session::has('success'))
{{-- 	<form-message :success="{{ Session::get('success') }}"></form-message>	 --}}
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
<div class="alert alert-danger">
	<strong>Errors:</strong>
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif