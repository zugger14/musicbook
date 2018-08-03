@extends('main')

@section('title', '| Home')
<style type="text/css">
  
  .carousel-inner > .item >img {
  min-height : 300px;
  max-height : 300px;
  width : 100%;
}
/*.container{
  height:300px;
}*/
</style>

@section('navbar_title')
    <div class="logo"><b><span>Mu</span>si<span>c</span> Bo<span>ok</span></b></div>
@endsection

@section('content')

<div class="container-fluid">
<div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1" class=""></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img class="first-slide" src="{{ asset('3.jpg') }}" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Sign Up Now!</h1>
              <p> Musicbook can be used by anyone from music following fans as well as music making artists. It's free to explore as well as to promote your songs.</p>
              <p><a class="btn btn-lg btn-primary" href="{{ route('register') }}" role="button">Sign up now</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="{{ asset('2.png') }}" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Start Browsing Songs</h1>
              <p style="color:black;">You can listen to some trending songs wihtout creating accounts.However the number of songs you get to listen are limited until you get yourself a free account registration. </p>
              <p><a class="btn btn-lg btn-primary" href="#browse" role="button">Browse gallery</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

<div class="row">
    <song-home></song-home>
</div>


@endsection

<script type="text/javascript">
    $('#myCarousel').carousel();
</script>
