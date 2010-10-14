<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; 

?>
<!-- [listing 6-4] -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Hovering Toolbar</title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $api_key ?>" type="text/javascript"></script>
	<script src="map_data.php" type="text/javascript"></script>
	<script src="map_functions.js" type="text/javascript"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="map"></div>
	<div id="toolbar">
		<h1>Cell-Tower Locations</h1>
		<ul id="options">
			<li><a href="#">Towers</a></li>
			<li><a href="#">Poles</a></li>
			<li><a href="#">Masts</a></li>
			<li><a href="#">Other</a></li>
		</ul>
	</div>
</body>
</html>
<!-- [listing 6-4 end] -->