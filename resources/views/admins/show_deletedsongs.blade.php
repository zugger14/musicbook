@extends('main')
@section('title', 'Users')
@section('navbar_title', 'Music Book')

@section('content')

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3> Deleted Songs <small class="badge"> {{ App\Song::where('status', '=', 'deleted')->count() }}</small></h3>
			</div>
			<div class="title_right">
				<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
					<div class="input-group">
						<span class="input-group-btn">
{{-- 		    				<song-upload :isArtist="false" :tags="{{ $tags }}"></song-upload>     --}}
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					
					<div class="x_content">

						<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>cover pic</th>
									<th>Name</th>
									<th>Type</th>
									<th>Play Count</th>
									<th>Status</th>
									<th>Added Date</th>
									<th>description</th>
								</tr>
							</thead>
							<tbody>
								@foreach($songs as $song)
									<tr>
										<td> <img src="{{ $song->image }}" width=" 50px" height="50px" alt="avatar pic"></td>
										<td>{{ $song->title }}</td>
										<td>{{ $song->upload_type }}</td>
										<td>{{ $song->played_time }}</td>
										<td>{{ $song->status }}</td>
										<td>{{ $song->created_at }}</td>
										<td>{{ $song->song_description }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
    <!-- icheck.js -->
    <script src="{{ asset('admin-template/vendors/iCheck/icheck.min.js') }}"></script>
    {{-- datatables --}}
    <script src="{{ asset('admin-template/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin-template/vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <script src="{{ asset('admin-template/build/js/custom.min.js') }}"></script>
@endsection


