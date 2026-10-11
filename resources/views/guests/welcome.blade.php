@extends('main')

@section('title', '| Home')
@section('navbar_title')
    <span style="font-size: 20px;">🎵</span> <span style="font-weight: 800; font-family: 'Space Grotesk', sans-serif;">Music<span style="color: #a78bfa;">Book</span></span>
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
              <h1>The Sound of Tomorrow, Today</h1>
              <p>Connect with emerging artists, discover exclusive unreleased tracks, and share your sonic journey with music fans worldwide.</p>
              <p><a class="btn btn-lg btn-primary" href="{{ route('register') }}" role="button">Start Listening Free</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="{{ asset('2.png') }}" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Explore Trending Releases</h1>
              <p>Stream high fidelity music from independent creators or upload your own masterpieces directly to the global community.</p>
              <p><a class="btn btn-lg btn-primary" href="#browse" role="button">Browse Collection</a></p>
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
