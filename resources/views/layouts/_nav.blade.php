<!-- default navbar -->
<nav class="navbar navbar-default navbar-fixed-top" style="text-align: center;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="opacity: 0.5">@yield('navbar_title')</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if (Auth::check() && Auth::user()->is_artist)
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('artist/home')?'active':'' }}" >
                        <a href="{{ route('artist.home') }}">Home</a>
                    </li>
               {{--  <li><song-upload></song-upload></li>
 --}}                    
                </ul>
            @endif    
            <ul class="nav navbar-nav navbar-right">

                @if (Auth::guard('web')->check() && Auth::user()->is_artist)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="">profile(artist)</a></li>
                        <li><a href="">tracks</a></li>
                        <li><a href="">account settings</a></li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                Logout
                            </a>    
                            <form id="frm-logout" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>

                @elseif (Auth::guard('web')->check() && !Auth::user()->is_artist)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="">profile(fans)</a></li>
                            <li><a href="">who to follow</a></li>
                            <li><a href="">account settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                    Logout
                                </a>    
                                <form id="frm-logout" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                  {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>

                @elseif (Auth::guard('admin')->check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('admin')->user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="">admin profile</a></li>
                            <li><a href="">who to follow</a></li>
                            <li><a href="">account settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                                    Logout
                                </a>    
                                <form id="frm-logout" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                  {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    <div class="signing">
                        <a href=" {{ route('login') }}" class="btn btn-md btn-default" style="border-radius: 0px; margin-top: 7px; ">Sign in</a>
                        <a href="{{ route('register') }}" class="btn btn-md btn-default" style="border-radius: 0px; margin-top: 7px;">Create account</a>
                    </div>
                @endif

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>