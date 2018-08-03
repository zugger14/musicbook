@if (Auth::guard('admin')->check())    {{--  Request::is('admin')  for allorwing users and admin login in same browser without loggin out any one aacount.
 --}}	@include('layouts._head-admin')
		<div id="app">
			<body class="nav-md"> {{-- class name same as in admin template from index file --}}
				@include('layouts._nav-admin')
			    @include('layouts._messages-admin')
		      	@yield('content')
			    @include('layouts._footer-admin')
			</body>
		</div><!-- end of app div -->
	    @include('layouts._javascript-admin')    
		@yield('scripts')
	</html>

@else
	@include('layouts._head')
		<div id="app">
			<body>
				@include('layouts._nav')
			    @include('layouts._messages')
			    <div class="container-fluid" style="margin-top:0px;">
			      @yield('content')
			    </div>
			    @include('layouts._footer')
			</body>
		</div><!-- end of app div -->
	    @include('layouts._javascript')    
		@yield('scripts')
	</html>
@endif
