<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $albums = \App\Album::where('public', 1)->get();
    return view('welcome', compact('albums'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('album', 'AlbumController');

Route::prefix('/album/{album}')->group(function () {


    Route::resource('photo', 'PhotoController');

    Route::prefix('/photo/{photo}')->group(function () {

        Route::resource('comment', 'CommentController');


    });

});
//Route::get('/gallery/{user}','AlbumController@gallery');
Route::get('/gallery/{blogname}','AlbumController@gallery');

//Route::get('/photo/create/{albumid}','PhotoController@photoinalbum');
