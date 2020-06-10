@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="Post" action="/album/{{$album->id}}"  enctype="multipart/form-data">
            @csrf
            @method('PUT')
            Name <input name="name" value="{{ $album->name }}"><br>
            Description <input name="description" value="{{ $album->description }}"><br>
            Public ? <input type="radio" id="public" name="visibility" value="public" {{$album->public === "1" ? "checked=\"checked\"" : ""}}   >
            <label for="male">Public</label>
            <input type="radio" id="private" name="visibility" value="private" {{$album->public === "0" ? "checked=\"checked\"" : ""}}>
            <label for="female">Private</label><br>
            <input type="submit" value="Edit Album" name="submit">
        </form>
    </div>
@endsection
