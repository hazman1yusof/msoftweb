<!DOCTYPE html>
<html>
<head>
	<title>Preview Video</title>
</head>
<body>
	<div class="video-container">
	<video width="100%" controls autoplay  loop>
		<source src="{{ url('uploads/'.$video->attachmentfile)}}" type="{{$video->type}}">
			<!-- <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4"> -->
		Your browser does not support HTML5 video.
	</video>
	</div>

</body>
</html>