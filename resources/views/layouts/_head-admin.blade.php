<!DOCTYPE html>

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Music Book @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- custom style for form element margin and spacing -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Bootstrap -->
    <link href="{{  asset('admin-template/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{  asset('admin-template/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('admin-template/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{  asset('admin-template/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    
    <!-- bootstrap-progressbar -->
    <link href="{{  asset('admin-template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{  asset('admin-template/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet')"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{  asset('admin-template/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('admin-template/build/css/custom.min.css') }}" rel="stylesheet">

    <!-- Datatables -->
    <link href="{{  asset('admin-template/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" >
    <link href="{{  asset('admin-template/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" >
    <link href="{{  asset('admin-template/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" >
    <link href="{{  asset('admin-template/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" >
    <link href="{{  asset('datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" >

    @yield('stylesheets')
    <script>
    
      window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        ]) !!};
    </script>
  </head>
