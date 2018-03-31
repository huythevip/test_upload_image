@extends('layouts.base')

@section('css')

.body-title {
	text-align: center;
}

img {
	width: 200px;
	height: 200px;
}

.create_new_album {
	margin-left: 15px;
}

@endsection

@section('content')
<div class="row">
<div class="body-title">
<h1>{{ $album->name }}</h1>
<h3>{{ $album->description }}</h3>
</div>
</div>
<div class="row" style="margin-bottom: 20px">
<button type="button" class="btn btn-info create_new_album" data-toggle="modal" data-target="#addPhoto">Add new Photo</button>
</div>


<div class="row">
@foreach($album->photos as $photo)
<div class="col-xs-2">
	<div class="thumbnail">
		<img src="{{ URL::asset( $photo->url ) }}" alt="{{ $album->name }} picture">
		<div class="caption">
			{{ $photo->description }}
		</div>
	</div>
</div> 
@endforeach
</div>



<!-- Modal -->
<div id="addPhoto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add new photo to album {{ $album->name }}</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="photos/attatch" enctype="multipart/form-data">
	{{ csrf_field() }}
<div class="form-group">
  <label for="name">Photo Name:</label>
  <input type="text" class="form-control" id="name" name="photoName">
  <input type="text" name="albumId" class="hidden" value="{{ $album->id }}">
  <input type="text" name="albumName" class="hidden" value="{{ $album->name }}">
</div>
<div class="form-group">
  <label for="file">Upload photo:</label>
  <input type="file" name="uploadedFile" id="file">
</div>
<div class="form-group">
  <label for="desc">Photo description:</label>
  <textarea class="form-control" rows="5" id="desc" name="photoDescription"></textarea>
</div>
<button type="submit" class="btn btn-success">Add new Photo</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')

@endsection