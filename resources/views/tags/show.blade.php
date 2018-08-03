@extends('main')
@section('title', "| $tag->name Tag")
@section('content')

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Users <small class="badge">{{  App\User::count() }}</small></h3>
			</div>
			<div class="title_right">
				<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
{{-- 					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search for...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Go!</button>
						</span>
					</div> --}}
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					
					<div class="x_content">
						<div class="row">
							<div class="col-md-8">
								<h2>{{ $tag->name }}<div class="badge">{{ $tag->songs()->count() }}</div></h2>
							</div>
							<div class="col-md-2">
								<a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-primary btn-block pull-right btn-top-spacing">edit</a>
							</div>
							<div class="col-md-2">
								<form method="post" action="{{ route('tags.destroy', $tag->id) }}">
									{{ csrf_field() }}
									{{ method_field('delete') }}
									<button type="submit" class="btn btn-danger btn-block pull-right btn-top-spacing">delete</button>
								</form>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>title</th>
										<th>tags</th>
									</tr>
								</thead>
								<tbody>
								@foreach ($tag->songs as $song)
									<tr>
										<th>{{ $song->id }}</th>
										<td>{{ $song->title}}</td>
										<td>
											@foreach ($song->tags as $tag)
												<span class="label label-info">{{ $tag->name }}</span>
											@endforeach
										</td>
										{{-- <td><a href="{{ route('songs.show', $song->id)}}" class="btn btn-default btn-sm">view</a></td> --}}
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

