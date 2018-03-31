@extends('layouts.base')

@section('css')

@endsection

@section('content')
<form method="POST" action="{{ route('albums_upload') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
<div class="form-group">
  <label for="name">Album Name:</label>
  <input type="text" class="form-control" id="name" name="albumName">
</div>
<div class="form-group">
  <label for="file">Select album cover:</label>
  <input type="file" name="uploaded_file" id="file">
</div>
<div class="form-group">
  <label for="desc">Description:</label>
  <textarea class="form-control" rows="5" id="desc" name="albumDescription"></textarea>
</div>
<button type="submit" class="btn btn-success">Create new album</button>
</form>
@endsection