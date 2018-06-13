@extends('main')
@section('title', 'Collections')
@section('navbar_title', 'Collections')

@section('content')
    <div class="row">
        <div class="col-md-8" >
            <purchasedsong-view></purchasedsong-view>
        </div>
        <div class="col-md-4">
        	<favourite-view></favourite-view>
		</div>        	
    </div>

@endsection
<style type="text/css">
	[v-cloak] {
  display: none;
}
</style>
