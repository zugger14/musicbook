@extends('main')
@section('title', 'Home')
@section('navbar_title', 'Music Book')

@section('content')
	<!-- page content -->

        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="panel panel-default" style="margin-top: 100px;">
            
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
              <div class="count green">{{ App\User::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-male"></i> Total Males</span>
              <div class="count">{{ App\User::where('gender', 1)->get()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-female"></i> Total Females</span>
              <div class="count">{{ App\User::where('gender', 0)->get()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-audible"></i> Total Songs</span>
              <div class="count">{{ App\Song::where('status', 'present')->get()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-archive"></i> Total Playlists</span>
              <div class="count">{{ App\Playlist::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Total Live Events</span>
              <div class="count">{{ App\LiveEvent::all()->count() }}</div>
            </div>
          </div>
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-book"></i> Total Notes</span>
              <div class="count">{{ App\Note::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-tags"></i> Total Tags</span>
              <div class="count">{{ App\Tag::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-inbox"></i> Total Private Messages</span>
              <div class="count">{{ App\PrivateMessage::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-thumbs-o-up"></i> Total Song Likes</span>
              <div class="count">{{ App\Like::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-comment"></i> Total Song Comments</span>
              <div class="count">{{ App\Comment::all()->count() }}</div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-share"></i> Total Song Shares</span>
              <div class="count">{{ App\Share::all()->count() }}</div>
            </div>
          </div>
          <!-- /top tiles -->
          </div>
         </div>
        <!-- /page content -->
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="{{ asset('admin-template/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('admin-template/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- FastClick -->
    <script src="{{ asset('admin-template/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('admin-template/vendors/nprogress/nprogress.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('admin-template/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <!-- gauge.js -->
    <script src="{{ asset('admin-template/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ asset('admin-template/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('admin-template/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Skycons -->
    <script src="{{ asset('admin-template/vendors/skycons/skycons.js') }}"></script>
    <!-- Flot -->
    <script src="{{ asset('admin-template/vendors/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/Flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/Flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/Flot/jquery.flot.resize.js') }}"></script>
    <!-- Flot plugins -->
    <script src="{{ asset('admin-template/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <!-- DateJS -->
    <script src="{{ asset('admin-template/vendors/DateJS/build/date.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('admin-template/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('admin-template/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('admin-template/build/js/custom.min.js') }}"></script>
@endsection

