@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="Post" action="/album"  enctype="multipart/form-data">
            @csrf
            Name <input name="name" value="{{ old('name') }}"><br>
            Description <input name="description" value="{{ old('description') }}"><br>
            Public ? <input type="radio" id="public" name="visibility" value="public"  checked="checked" >
            <label for="male">Public</label>
            <input type="radio" id="private" name="visibility" value="private">
            <label for="female">Private</label><br>
            Select Photo <input type="file" name="fileToUpload" id="fileToUpload" ><br>
            <input type="submit" value="Add Album" name="submit">
        </form>
    </div>
@endsection
