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
<h1>Welcome to my Album Show</h1>
</div>
</div>
<div class="row" style="margin-bottom: 20px">
<a href="{{route('albums_create')}}" class="btn btn-info create_new_album">Create new Album</a>
</div>




<div class="row">
@foreach($albums as $album)
<div class="col-xs-2 albumDetail">
	<div class="thumbnail">
		<a href="albums/{{ $album->id }}/photos"><img src="{{ $album->cover_image }}"></a>
		<div class="caption">
			<p>{{ $album->description }}</p>
			<p class="albumId hidden">{{ $album->id }}</p>
			<button type="button" data-toggle="modal" data-target="#editAlbum{{ $album->id }}" class="btn btn-warning btn_edit">Edit</button>
			<button class="btn btn-danger btnDelete">Delete</button>
			<button class="btn btn-danger hidden btnConfirmDelete">Confirm</button>
		</div>
	</div>
</div>

	<!-- Modal -->
<div id="editAlbum{{ $album->id }}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modify Album: {{ $album->name }} </h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="albums/edit" enctype="multipart/form-data">
	{{ csrf_field() }}
<div class="form-group">
  <label for="name">New Album Name:</label>
  <input type="text" class="form-control" id="name" name="newAlbumName" value="{{ $album->name }}">
  <input type="text" class="form-control hidden" name="oldAlbumId" value="{{ $album->id }}">
</div>
<div class="form-group">
  <label for="file">Upload new Album cover:</label>
  <input type="file" name="uploadedFile" id="file">
</div>
<div class="form-group">
  <label for="desc">New album description:</label>
  <textarea class="form-control" rows="5" id="desc" name="newAlbumDesc">{{ $album->description }}</textarea>
</div>
<button type="submit" class="btn btn-success">Save changes</button>
</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endforeach
</div>
<!-- Modal -->

@endsection



@section('scripts')
<script>
	$(document).ready(function() {
		$('.btnDelete').click(function() {
			$(this).addClass('hidden');
			$(this).siblings('.btnConfirmDelete').removeClass('hidden');
		}); //End of clicking delete button

		$('.btnConfirmDelete').click(function() {
				var albumId = $(this).siblings('.albumId').text();
			 $.ajax({
				url: 'api/albums/delete',
				data: {albumId},
				dataType: 'JSON',
				method: 'POST',
				success: function(response) {
					alert(response.message);
				}, //End of success
			}); //End of ajax
			$(this).parents('.albumDetail').addClass('hidden');
		}); //End of clicking confirm delete button
	}) // End of document ready

</script>
@endsection