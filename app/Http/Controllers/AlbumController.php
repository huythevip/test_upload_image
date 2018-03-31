<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use App\Photo;
use DB;
use File;


class AlbumController extends Controller
{
    public function Albums() {

    	$albums = Album::all();
    	return view('index', ['albums' => $albums]);
    }

    public function AlbumsCreate() {
    	return view('album_create');
    }

    public function AlbumUpload(request $request) {
    	$album = $request->file('uploaded_file');
    	$newCoverName = $request->albumName.'_cover.'.$album->getClientOriginalExtension();
    	$album->move(public_path('images'), $newCoverName);

    	File::makeDirectory(public_path('images').'/'.$request->albumName);

    	$newAlbum = new Album;
    	$newAlbum->name = $request->albumName;
    	$newAlbum->description = $request->albumDescription;
    	$newAlbum->cover_image = 'images/'.$newCoverName;
    	$newAlbum->file_name = $newCoverName;
    	$newAlbum->file_extension = $album->getClientOriginalExtension();
    	$newAlbum->save();

    	return redirect('albums');

    }

    public function AlbumDetail($id) {
    	$album = Album::find($id);
    	return view('album_detail', [
    		'album' => $album,
    	]);
    }

    public function PhotoAttatch(request $request) {
    	$photo = $request->file('uploadedFile');
    	$newName = $request->photoName.'.'.$photo->getClientOriginalExtension();
    	$photo->move(public_path('images/'.$request->albumName), $newName);

    	$newPhoto = new Photo;
    	$newPhoto->album_id = $request->albumId;
    	$newPhoto->title = $request->photoName;
    	$newPhoto->description = $request->photoDescription;
    	$newPhoto->url = 'images/'.$request->albumName.'/'.$newName;
    	$newPhoto->file_name = $newName;
    	$newPhoto->file_extension = $photo->getClientOriginalExtension();

    	$newPhoto->save();

    	return back();

    }

    public function AllPhotos() {
    	$albums = Album::all();

    	return view('all_photos', [
    		'albums' => $albums,
    	]);

    }

    public function PhotosUpload(request $request) {

    		$uploadPhoto = $request->file('uploaded_file');
    		$uploadPhoto->move(public_path('images/'.$request->album_name), $uploadPhoto->getClientOriginalName());

    		$albumObject = DB::table('albums')->where('name', '=', $request->album_name)->first();
    		$albumId = $albumObject->id;

    		$newPhoto = new Photo;
    		$newPhoto->title = $request->photo_name;
    		$newPhoto->album_id = $albumId;
    		$newPhoto->description = $request->photo_desc;
    		$newPhoto->url = 'images/'.$request->album_name.'/'.$uploadPhoto->getClientOriginalName();
    		$newPhoto->file_name = $uploadPhoto->getClientOriginalName();
    		$newPhoto->file_extension = $uploadPhoto->getClientOriginalExtension();
    		$newPhoto->save();

    	return back();
    }

    public function PhotosEdit(request $request) {

    	$selected_album = DB::table('albums')->where('name', '=', $request->new_album)->first();

    	$photo = Photo::find($request->photo_id);
    	$photo->description = $request->new_description;
    	$photo->album_id = $selected_album->id;
    	$photo->save();

    	return json_encode(['message' => 'Updated photo information!']);
    }

    public function PhotosDelete(request $request) {
    	$photo = Photo::find($request->photo_id);
    	File::delete(public_path($photo->url));
    	$photo->delete();

    	return json_encode(['message' => 'Photo deleted permanently!']);
    }

    public function AlbumsEdit(request $request) {
    	//Change album name and change directory name
    	$albumBeingEdit = Album::find($request->oldAlbumId);

    	$oldAlbumName = $albumBeingEdit->name;
    	$oldFileName = $albumBeingEdit->file_name;

    	//Change Album name
    	$albumBeingEdit->name = $request->newAlbumName;


    	//New File Name of Cover (ABC_cover.pjg)
    	$newFileName = $request->newAlbumName.'_cover.'.$albumBeingEdit->file_extension;

    	
    	//Change ALbum directory and cover image's name
    	File::move(public_path('images/'.$oldAlbumName), public_path('images/'.$request->newAlbumName));
    	File::move(public_path('images/'.$oldFileName), public_path('images/'.$newFileName));

    	//Change album File_name & Cover Image before processing newly uploaded photos.
    	$albumBeingEdit->file_name = $newFileName;
    	$albumBeingEdit->cover_image = 'images/'.$newFileName;


    	//Change url of photos belonging to album.
    	$photosInAlbumEdit = Photo::where('album_id', '=', $albumBeingEdit->id)->get();
    	foreach($photosInAlbumEdit as $photo) {
    		$photo->url = 'images/'.$request->newAlbumName.'/'.$photo->file_name;
    		$photo->save();
    	} //End foreach

    	
    	if ($request->hasFile('uploadedFile')) {
    		File::delete($albumBeingEdit->cover_image);
    		$newAlbumCover = $request->file('uploadedFile');
    		$newAlbumCover->move(public_path('images/'), $newFileName);
    		$albumBeingEdit->cover_image = 'images/'.$newFileName;
    		};
    	//Change Album Description
    	$albumBeingEdit->description = $request->newAlbumDesc;
    	$albumBeingEdit->save();

    	return redirect()->back();
    }

    public function AlbumsDelete(request $request) {
    	$album = Album::find($request->albumId);
    	DB::table('photos')->where('album_id', '=', $request->albumId)->delete();
    	File::delete(public_path($album->cover_image));
    	File::deleteDirectory(public_path('images/'.$album->name));
    	$album->delete();

    	return json_encode(['message' => 'Album deleted permanently']);
    }

}
