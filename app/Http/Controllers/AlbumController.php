<?php

namespace App\Http\Controllers;

use App\Album;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'show','gallery'
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $albums = null;
//        if (Auth::check())
        $albums = auth()->user()->albums;
//        $albums = auth()->user()->albums()->get();
        //$albums = Auth::user()->albums()->get();

        return view('Album.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Album.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'fileToUpload' => 'required'
        ]);

        $filenameWithExtension = $request->file('fileToUpload')->getClientOriginalName();

        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

        $extension = $request->file('fileToUpload')->getClientOriginalExtension();

        $filenameToStore = $filename . '_' . time() . '.' . $extension;


        $request->file('fileToUpload')->storeAs('public/album_covers', $filenameToStore);


//        $album = new Album();
//        $album->user_id = auth()->user()->id;
//        $album->name = $request->input('name');
//        $album->description = $request->input('description');
//        $album->filename= $filenameToStore;

        $visibility = false;
        if ($request->input('visibility') == "public")
            $visibility = true;
//        $album->public = $visibility;

        Album::create(array_merge($request->only('name', 'description')
            , ['filename' => $filenameToStore], ['user_id' => auth()->user()->id]
            , ['public' => $visibility]));

//        $album->save();

        return redirect('/album')->withErrors(['success' => "Album created successfully"]);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
//        $album = Album::find($id);
//        if ($album == null)
//            return "Album not found";

//        $photos = Photo::where('album_id',$album->id)->get();

        $user = null;
        if (auth::check())
            $user = auth()->user();

        if (($user != null && $album->user_id == $user->id) || $album->public == 1) {
            $ownit = false;
            if ($user != null && $user->id == $album->user_id)
                $ownit = true;

            return view('Album.show', compact('album', 'ownit'));
        } else {
            return "You dont have access to this page";
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
//        $album = Album::find($id);

        return view('Album.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

//        $album = Album::find($id);
        //$album->user_id = auth()->user()->id;

//        $album->name = $request->input('name');
//        $album->description = $request->input('description');

        $visibility = false;
        if ($request->input('visibility') == "public")
            $visibility = true;
//        $album->public = $visibility;

        $album->update(array_merge($request->only('name', 'description')
            , ['public' => $visibility]));

        $album->save();

        return redirect('/album')->withErrors(['success' => "Album updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
//        $album = Album::find($id);

        if (Storage::delete('public/album_covers/' . $album->filename)) {
            $album->delete();

            return redirect('/album')->withErrors(['success' => 'Photo deleted successfully!']);
        }

    }

    public function gallery($blogname)
    {
//        dd(User::where('blogname',$blogname)->get());
//        $user = User::where('blogname',$blogname)->first();
//        $albums = Album::where('user_id',$user->id)->get();
        $albums = User::query()->where('blogname', $blogname)->first()->albums->where('public',1);
        return view('Album.index', compact('albums'));
    }
//
//    public function gallery(User $user)
//    {
//        return view('Album.index', ['albums' => $user->albums]);
//    }
}
