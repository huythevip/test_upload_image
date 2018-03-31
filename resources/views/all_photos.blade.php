@extends('layouts.base')

@section('css')

.body-title {
	text-align: center;
}

img {
	width: 200px;
	height: 200px;
}

.create_new_photo {
	margin-left: 15px;
}

@endsection

@section('content')
<div class="row">
<div class="body-title">
<h1>Below are all of our photos!</h1>
</div>
</div>
<div class="row" style="margin-bottom: 20px">
<button type="button" class="btn btn-info create_new_photo" data-toggle="modal" data-target="#addPhoto">Add new Photo</button>
</div>


<div class="row">
@foreach($albums as $album)
@foreach($album->photos as $photo)
<div class="col-xs-2 picture">
	<div class="thumbnail">
		<img src="{{ URL::asset( $photo->url ) }}" alt="{{ $album->name }} picture">
		<div class="caption">
			<p class="photo_id hidden">{{ $photo->id }}</p> 
			<p class="photo_desc">{{ $photo->description }}</p>
			<p class="album_name">Album: <span class="select_album">{{ $album->name }}</span></p>
			<button class="btn btn-warning btn_edit">Edit</button>
			<button class="btn btn-warning hidden btn_save">Save</button>
			<button class="btn btn-danger btn_delete">Delete</button>
			<button class="btn btn-danger hidden btn_delete_confirm">Confirm</button>
		</div>
	</div>
</div>
@endforeach 
@endforeach
</div>



<!-- Modal -->
<div id="addPhoto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add new photo to album you want!</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="photos/upload" enctype="multipart/form-data">
	{{ csrf_field() }}
<div class="form-group">
  <label for="name">Photo Name:</label>
  <input type="text" class="form-control" id="name" name="photo_name">
  <label style="margin-top: 15px" for="album_name">Select album to put in:</label>
  <select id="album_name" name="album_name" class="form-control">
  	@foreach($albums as $album)
  		<option>{{ $album->name }}</option>
  	@endforeach
  </select>
</div>
<div class="form-group">
  <label for="file">Upload photo:</label>
  <input type="file" name="uploaded_file" id="file">
</div>
<div class="form-group">
  <label for="desc">Photo description:</label>
  <textarea class="form-control" rows="5" id="desc" name="photo_desc"></textarea>
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


<!-- Script -->
@section('scripts')
<script>
	$(document).ready(function() {
		$('.btn_edit').click(function() {
			var old_photo_desc = $(this).siblings('.photo_desc').text();
			var old_album_name = $(this).siblings('.album_name').text();
			var html = '<select class="form-control select_new_album">';
			
			{!! json_encode($albums) !!}.forEach(function(album) {
				html += '<option>'+album.name+'</option>';
			}); //End of for loop
			html += '</select>';
			$(this).siblings('.photo_desc').replaceWith('<input class="form-control" name="new_photo_desc" value="'+old_photo_desc+'">');
			$(this).siblings('.album_name').children('span').replaceWith(html);
			$(this).addClass('hidden');
			$(this).siblings('.btn_save').removeClass('hidden');
		}); //End of clicking button edit

		$(document).on('click', '.btn_save', function() {
			var new_description = $('input[name=new_photo_desc]').val();
			var photo_id = $(this).siblings('.photo_id').text();
			var new_album = $('select.select_new_album option:selected').text();
			$.ajax({
				url: "api/photos/edit",
				method: "POST",
				data: {new_description, photo_id, new_album},
				dataType: "JSON",
				success: function(response) {
					alert(response.message);

				}, //End of success function
			}); //End of ajax
			$('input[name=new_photo_desc]').replaceWith('<p class="photo_desc">'+new_description+'</p>');
			$('select.select_new_album').replaceWith('<span class="select_album">'+new_album+'</span>');
			$(this).addClass('hidden');
			$(this).siblings('.btn_edit').removeClass('hidden');
		}); //End of clicking button save new edit

		$('.btn_delete').click(function() {
			$(this).addClass('hidden');
			$(this).siblings('.btn_delete_confirm').removeClass('hidden');
		}) //End of clicking delete button

		$('.btn_delete_confirm').click(function() {
			var photo_id = $(this).siblings('.photo_id').text();
			$(this).parents('.picture').addClass('hidden');
			$.ajax({
				url: 'api/photos/delete',
				data: {photo_id},
				dataType: 'JSON',
				method: 'POST',
				success: function(response) {
					alert(response.message);
				}, //End of success
			}); //End of ajax
			$(this).addClass('hidden');
			$(this).siblings('.btn_delete').removeClass('hidden');
		}); //End of clicking confirm button
	}) //End of document ready

</script>
@endsection