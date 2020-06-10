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
        <a href="/album/create" class="btn btn-sm btn-primary">Make Album</a><br>
        <br>
        <div class="row">
            @foreach($albums as $album)

                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="/storage/album_covers/{{$album->filename}}"  height="225">
                        <div class="card-body">
                            <p class="card-text">{{$album->name}}<br>{{$album->description}}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="/album/{{$album->id}}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <a href="/album/{{$album->id}}/edit" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>

                                <small class="text-muted">{{$album->photos->count()}}<br><a href="/gallery/{{$album->user->blogname}}">{{$album->user->name}}</a></small>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>


    </div>
@endsection
