<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="/" class="site_title"><i class="fa fa-book"></i> <span>MB Admin Panel</span></a>
				</div>

				<div class="clearfix"></div>

				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
						<img src="{{ 'https://www.gravatar.com/avatar/' . md5(Auth::guard('admin')->user()->email) }}" alt="..." class="img-circle profile_img">
					</div>
					<div class="profile_info">
						<span>Welcome,</span>
						<h2>{{ Auth::guard('admin')->user()->name }}</h2>
					</div>
				</div>
				<!-- /menu profile quick info -->

				<br />

				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
					<div class="menu_section">
						<h3>General</h3>
						<ul class="nav side-menu">
							<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Home </a>
							</li>
							<li><a><i class="fa fa-edit"></i> Users <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('users.index') }}">Show All Users</a></li>
									<li><a href="{{ route('users.create') }}">Add User</a></li>
									{{-- <li><a href="form_validation.html">User Profile</a></li> --}}
									<li><a href="{{ route('users.deletedusers') }}">Deleted/Banned Users</a></li>
								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i> Songs <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('songs.all') }}">Show All Songs</a></li>
									<li><a href="{{ route('songs.deleted') }}">Deleted Songs</a></li>
									{{-- <li><a href="form_advanced.html">Add Song</a></li> --}}
										<li><a>Artist Songs<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="{{ route('song.artists') }}">Show All Songs</a>
												</li>
{{-- 												<li><a href="#level2_1">Liked Songs</a>
												</li>
												<li><a href="#level2_2">Shared Songs</a> 												</li>
												<li><a href="#level2_2">Deleted Songs</a>
												</li>--}}


											</ul>
										</li>
										<li><a>Fan Songs<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="{{ route('song.fans') }}">Show All Songs</a>
												</li>
{{-- 												<li><a href="#level2_1">Liked Songs</a>
												</li>
												<li><a href="#level2_2">Shared Songs</a> 
												</li>
												<li><a href="#level2_2">Deleted Songs</a>
												</li>--}}

											</ul>
										</li>
									</li>
								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i> Playlists <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('playlists.index') }}">Show All Playlists</a></li>
								{{-- 	<li><a href="form_advanced.html">Add New Playlist</a></li> --}}
								{{-- 	<li><a href="{{ route('playlists.deleted') }}">Deleted Playlists</a></li>
 --}}								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i> Live Events <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('liveevents.allLiveEvents') }}">Show All Live Events</a></li>
{{-- 									<li><a href="#">Show Upcoming Live Events</a></li>
									<li><a href="form_wizards.html">Show Completed Live Events</a></li>
									<li><a href="form_wizards.html">Show Active Live Events</a></li>
									<li><a href="form_advanced.html">Add New Live Event</a></li>
									<li><a href="form_validation.html">Deleted Live Events</a></li> --}}
								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i> Notes <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('notes.allNotes') }}">Show All Notes </a></li>
								{{-- 	<li><a href="form_advanced.html">Advanced Components</a></li> --}}
								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i>Song Tags <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('tags.index') }}">All Song Tags</a></li>
								</ul>
							</li>
							<li><a><i class="fa fa-edit"></i> Reports <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu">
									<li><a href="{{ route('admin.reports') }}">Sale Reports</a></li>
									
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<!-- /sidebar menu -->
				<!-- /menu footer buttons -->
				<div class="sidebar-footer hidden-small">
					<a data-toggle="tooltip" data-placement="top" title="Settings">
						<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="FullScreen">
						<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="Lock">
						<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
					</a>
					<a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
						<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
					</a>
				</div>
				<!-- /menu footer buttons -->
			</div>
		</div>

		<!-- top navigation -->
		<div class="top_nav">
			<div class="nav_menu">
				<nav>
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					</div>

					<ul class="nav navbar-nav navbar-right">
						<li class="">
							<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<img src="images/img.jpg" alt="">{{ Auth::guard('admin')->user()->name }}
								<span class=" fa fa-angle-down"></span>
							</a>
							<ul class="dropdown-menu dropdown-usermenu pull-right">
								<li><a href="javascript:;">Help</a></li>
								<li>
									<a href="" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
										<i class="fa fa-sign-out pull-right"></i>Log Out
									</a>    
									<form id="frm-logout" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</li>
							</ul>
						</li>

						
					</ul>
				</nav>
			</div>
		</div>
		<!-- /top navigation -->
