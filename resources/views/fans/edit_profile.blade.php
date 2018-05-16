@extends('main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit your profile</div>

                <div class="panel-body">
                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="avatar">profile image</label>
                            <div id="image_previews" style="margin-top:10px;border-radius: 5px;background-color: whitesmoke; width: 100px; height: 100px;">
                                <img id='image' src="{{ $user->avatar}}" width="100px" height="100px" >
                            </div>
                            <input class="form-control" type="file" name="avatar" onchange="changeImage(event)" accept="image/">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input id="location" type="text" value="{{ $profile->location }}" class="form-control" name="location">
                        </div>
                        <div class="form-group">
                            <label for="about">About me</label>
                            <textarea name="about" id="about" cols="30" rows="10" class="form-control">{{ $profile->about }}</textarea>
                        </div>
                        <div class="from-group">
                            <p class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">save profile</button>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function changeImage(event){
        var blob,img;
        img=document.getElementById('image');
            img.src=URL.createObjectURL(event.target.files[0]); 
        }
    </script>
    @endsection
