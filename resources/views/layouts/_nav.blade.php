<!-- default navbar -->
<nav :class="'navbar navbar-default navbar-fixed-' + nav" style="text-align: center;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" style="opacity: 0.5">@yield('navbar_title')</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if (Auth::check() && Auth::user()->is_artist)
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('artist/home')?'active':'' }}" >
                        <a href="{{ route('artist.home') }}">Home</a>
                    </li>
                    <li class="{{ Request::is('artist/collections')?'active':'' }}" >
                        <a href="{{ route('artist.collections') }}">Collections</a>
                    </li>
                   {{--  <li><song-upload></song-upload></li>
                       --}}                    
                </ul>
            @endif

            @if (Auth::check() && !Auth::user()->is_artist)
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('fan/home')?'active':'' }}" >
                        <a href="{{ route('fan.home') }}">Home</a>
                    </li>
                    <li class="{{ Request::is('fan/collections')?'active':'' }}" >
                        <a href="{{ route('fan.collections') }}">Collections</a>
                    </li>
                </ul>
            @endif

            @if (Auth::guard('web')->check())
                <ul class="nav navbar-nav">
                    <li>
                        <search-users></search-users>
                    </li>
                </ul>
            @endif

            <ul class="nav navbar-nav navbar-right">
                
                <audio id='noty_audio' src="{{ asset('audio/notify.mp3') }}"></audio>
                @if (Auth::guard('web')->check())
                    <li>
                        <song-upload :isArtist="{{ Auth::user()->is_artist }}" :tags="{{ $tags }}" style="margin-top: -8px;"></song-upload>    
                    </li>
                @endif
                    @if (Auth::guard('web')->check()  && Auth::user()->is_artist)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile.show', Auth::user()->slug) }}">profile(artist)</a></li>
                            <li><a href="{{ route('artist.tracks') }}">Songs</a></li>
                            <li><a href="{{ route('artists.playlist', Auth::id())  }}">Playlists</a></li>
                            <li><a href="{{ route('event.index')  }}">Live Events</a></li>
                            <li><a href="{{ route('password.form') }}">account settings</a></li>
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
                        <li><a href="{{ route('profile.show', Auth::user()->slug) }}">profile(fans)</a></li>
                       {{--  <li><a href="">who to follow</a></li> --}}
                        <li><a href="{{ route('password.form') }}">account settings</a></li>
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
                {{-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">noticiations <span class="caret"></span></a>
                        <ul class="dropdown-menu"> --}}
               {{--          </ul>
                </li>
 --}}
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


                @if (Auth::guard('web')->check())
                    <pm-nav :user_id="{{ Auth::id() }}"></pm-nav>
                    <friend-requests :user_id="{{ Auth::guard('web')->id() }}"></friend-requests>
                    <notification 
                        :reads="{{ Auth::guard('web')->user()->readNotifications  }}"
                        :unreads="{{ Auth::guard('web')->user()->unreadNotifications  }}" 
                        :id="{{ Auth::guard('web')->id() }}" >
                    </notification>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
