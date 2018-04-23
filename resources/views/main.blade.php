@include('layouts._head')
	<div id="app">
		<body>    
			@include('layouts._nav')
		    @include('layouts._messages')
		    <div class="container-fluid" style="margin-top:70px;">
		      @yield('content')
		    </div>
		    @include('layouts._footer')
		</body>
	</div><!-- end of app div -->
    @include('layouts._javascript')    
	@yield('scripts')
</html>