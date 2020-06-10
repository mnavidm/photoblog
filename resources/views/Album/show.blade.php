@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-success">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <h1>{{$album->name}}</h1>
            <br>
        <div class="row">

            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="/storage/album_covers/{{$album->filename}}"  height="225">
                    <div class="card-body">
                        <p class="card-text">{{$album->name}}<br>{{$album->description}}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                @if($ownit)
{{--                                    <form method="get" action="/album/{{$album->id}}/photo/create">--}}
                                    <a href="{{route('photo.create', $album)}}" class="btn btn-sm btn-outline-primary">Add photo</a>
{{--                                    <form method="get" action="{{route('photo.create', $album)}}">--}}
{{--                                        <button type="submit" href="" class="btn btn-sm btn-outline-primary">Add Photo</button>--}}
{{--                                    </form>--}}
                                @endif
                            </div>
                            <small class="text-muted"><a href="/gallery/{{$album->user->blogname}}">{{$album->user->name}}</a></small>
                        </div>
                        @if($ownit)
                            <br>
                            <a href="/album/{{$album->id}}/edit" class="btn btn-sm btn-secondary">Edit Album</a>
                            <form method="post" action="/album/{{$album->id}}">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-sm btn-danger">Delete Album</button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
            <hr>

        <div class="row">

            @foreach($album->photos as $photo)

                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="/storage/photos/{{$photo->filename}}"  height="225">
                        <div class="card-body">
                            <p class="card-text">{{$photo->title}}<br>{{$photo->description}}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/storage/photos/{{$photo->filename}}" class="btn btn-sm btn-outline-secondary">View</a>
                                    @if($ownit)
                                        <a href="/album/{{$album->id}}/photo/{{$photo->id}}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method=post action="{{route('photo.destroy',[$album,$photo])}}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    @endif

                                </div>
                                <small class="text-muted">{{$photo->updated_at}}</small>
                            </div>
                            <br>
                            @foreach($photo->comments as $comment)
                                {{$comment->user->name}}<br>
                                {{$comment->comment}}<br>
                                @if($photo->isPhotoOwner() || $comment->user_id == auth()->id())
                                    <form method="post" action="{{route('comment.destroy',[$album,$photo,$comment])}}" >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"  class="btn btn-danger" >Delete</button>
                                    </form>
                                @endif
                                <hr>
                            @endforeach
                            @if (Auth::check())
                                <br>
                                <form method="post" action="{{route('comment.store',[$album,$photo])}}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="Comment">Please leave Comment</label>
                                        <input type="text" class="form-control" id="comment"  name="comment" placeholder="Enter comment">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            @else
                                <br>
                                Please login to write comment.
                            @endif
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

    </div>
@endsection
