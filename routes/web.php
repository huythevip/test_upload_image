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

Route::get('/albums', 'AlbumController@albums')->name('home');
Route::post('/albums/edit', 'AlbumController@AlbumsEdit')->name('albums_create');
Route::get('/albums/create', 'AlbumController@AlbumsCreate')->name('albums_create');
Route::post('/albums/upload/', 'AlbumController@AlbumUpload')->name('albums_upload');
Route::get('/albums/{id}/photos/', 'AlbumController@AlbumDetail');
Route::post('/albums/{id}/photos/attatch/', 'AlbumController@PhotoAttatch')->name('photos_attatch');


Route::get('/photos', 'AlbumController@AllPhotos')->name('home_photos');
Route::post('/photos/upload', 'AlbumController@PhotosUpload')->name('photos_upload');
