@extends('main')
@section('title', '| Upload New Song')
@section('navbar_title', 'Music Book')
@section('content')
<div class="col-md-10 col-md-offset-1">
	<h1>Add New Song</h1>
	<hr>
	<form enctype="multipart/form-data" method="POST" action="{{ route('songs.store') }}">
		{{ csrf_field() }}

            {{-- <label for="artist_id">Artist:</label>
			<select name="artist_id" class="form-control">
			@foreach ($artists as $artist)
				<option value="{{ $artist->id }}">{{ $artist->name }}</option>
			@endforeach
			</select>

			<label for="tags">Tags:</label>
			<select name="tags[]" class="form-control select2-multi" multiple="multiple">
			@foreach ($tags as $tag)
				<option value="{{ $tag->id }}">{{ $tag->name }}</option>
			@endforeach
		</select> --}}
<div class="row">
		<div class="form-group">
				<div class="col-md-6">
					<label for="feature_image">Choose feature image:</label>
					<div id="image_previews" style="border-radius: 5px;background-color: whitesmoke; width: 200px; height: 200px;">
						<img id='image' class="" src="" width="200px" height="200px" >
						<input class="form-control-file" type="file" name="feature_image" onchange="changeImage(event)">
					</div>
				</div>
		</div>
</div>

		<div class="form-group">
			<label for="title">Song Title:</label>
			<input type="text" name="title" value="{{ old('title') }}" class="form-control" required maxlength="255">
		</div>
		<label name="body" class="div-spacing-top">Song Description:</label>

		<textarea rows="8" name="song_desc" value="{{ old('song_desc') }}" class="form-control"></textarea>
		<input type="submit" style="margin-top: 20px;" class="btn btn-success btn-lg btn-block">
	</form>
</div>
@endsection 
@section('scripts')
<script type="text/javascript">function changeImage(event){
	var blob,img;
	img=document.getElementById('image');
                                                    img.src=URL.createObjectURL(event.target.files[0]); // event.target returns the element triggerring the event file upload.
                                                    /*blob=document.getElementById('file').files[0];-------:::::::::: -------- can also be done in this way getting file object seleted from file upload button
                                                    img.src=URL.createObjectURL(blob)*/

                                                }
                                            </script>
                                            @endsection