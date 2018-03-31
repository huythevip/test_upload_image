<!DOCTYPE html>
<html>
<head>
<title>Photo Album</title>
<style>

* {
	box-sizing: border-box;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
}



@yield('css')
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>


<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="{{ route('home') }}">Photo Albums</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="{{ route('home_photos') }}">Photos</a></li>
    </ul>
  </div>
</nav>
<div class="container">

@yield('content')
</div> <!-- End of container -->
@yield('scripts')
</body>
</html>