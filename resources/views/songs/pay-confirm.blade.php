@extends('main')
@section('title', '| Buy Song')
@section('navbar_title', 'Buy Song')
@section('content')

	<form action="{{ config('esewa.debug_url') }}" method="POST">
        <input value="22" name="tAmt" type="hidden">
        <input value="100" name="amt" type="hidden">
        <input value="1" name="txAmt" type="hidden">
        <input value="3" name="psc" type="hidden">
    <input value="2" name="pdc" type="hidden">
        <input value="testmerchant" name="scd" type="hidden">
        <input value="323" name="pid" type="hidden">
        <input value="{{ url('/success') }}" type="hidden" name="su">
        <input value="{{ url('/failure') }}" type="hidden" name="fu">
        <input value="proceed with payment" type="submit">
    </form>
    
@endsection 
