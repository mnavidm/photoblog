<?php

namespace App\Http\Controllers;

use App\Album;
use App\Photo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{

    public function photoinalbum(){
        return 456;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Album $album
     * @return int
     */
    public function create(Album $album)
    {

        return view('Photo.create',compact('album'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, Album $album)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'fileToUpload'=>'required|image'
        ]);

        /** @var User $user */
        $user = auth()->user();

        $user->albums()->findOrFail($album->id);//security check

        $filenameWithExtension = $request->file('fileToUpload')->getClientOriginalName();

        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);

        $extension = $request->file('fileToUpload')->getClientOriginalExtension();

        $filenameToStore = $filename . '_' . time() . '.' . $extension;

        $request->file('fileToUpload')->storeAs('public/photos', $filenameToStore);

        $album->photos()->create(array_merge($request->only('title', 'description'), ['filename' => $filenameToStore]));

        return redirect()->route('album.show',$album);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album,Photo $photo)
    {
//        $photo = Photo::findOrFail($photo);
//        $album = Album::findOrFail($album);

        return view('Photo.edit',compact('album','photo'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album,Photo $photo)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);

        $photo->update($request->only('title', 'description'));

        return redirect(route('album.show',$album));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album, Photo $photo)
    {

        if (Storage::delete('public/photos/'.$photo->filename)) {
            $photo->delete();
        }
        return redirect(route('album.show',$album));

    }
}
