@extends('main')
@section('title', 'Song Notes')
@section('navbar_title', 'Music Book | Song Notes')

@section('content')
    <div class="row">
        {{-- <show-playlist :user_id={{ $user_id }}></show-playlist> --}}
        <add-note :user_id={{ $user_id }}></add-note>
    </div>
@endsection

@section('scripts')
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=igf5jsqifqu8p7p0nr3fhvyxsbjf5xqhw1ubno8tx0byav86"></script>
    <script type="text/javascript">
        
            tinymce.init({
                    selector:'textarea', 
                    plugins: 'link code',
                    menubar: 'false'

            });

    </script>
@endsection
