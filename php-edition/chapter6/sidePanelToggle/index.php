<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Toggleable Side Panel</title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $api_key ?>" type="text/javascript"></script>	
	<script src="map_data.php" type="text/javascript"></script>
	<script src="map_functions.js" type="text/javascript"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body class="sidebar-right">
	<div id="map-wrapper">
		<div id="map"></div>
	</div>
	<!-- [listing 6-9] -->
	<div id="toolbar">
		<h1>Cell-Tower Locations</h1>
		<ul id="options">
			<li><a href="#">Towers</a></li>
			<li><a href="#">Poles</a></li>
			<li><a href="#">Masts</a></li>
			<li><a href="#">Other</a></li>
		</ul>
		<ul id="sidebar-controls">
			<li><a href="#" id="button-sidebar-hide">Hide</a></li>
			<li><a href="#" id="button-sidebar-show">Show</a></li>
		</ul>
	</div>
	<!-- [listing 6-9 end] -->
	<div id="sidebar">
		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin accumsan condimentum dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur fringilla, turpis luctus dignissim mattis, odio urna interdum orci, et placerat nisi leo at metus. Proin non dolor.</p>
		<p>Suspendisse elementum, urna quis accumsan condimentum, enim mauris tempor ligula, ut ullamcorper leo erat at pede. In mauris sem, aliquam in, bibendum sed, porta nec, quam. Phasellus lacus purus, elementum dignissim, ultrices a, sollicitudin in, metus. In vestibulum dui pulvinar nunc.</p>
		<p>Etiam eu lacus quis nulla iaculis laoreet. Praesent hendrerit lacus eu ante. Duis purus sem, fringilla aliquam, ullamcorper vulputate, eleifend vitae, tortor. Morbi leo tortor, pretium at, viverra id, faucibus non, mauris. Vivamus eget mi. Donec varius. Fusce tellus quam, pharetra et, cursus eget, scelerisque eget, est. Quisque sit amet libero. Sed vel tortor. Duis justo nisi, pretium et, vestibulum commodo, rhoncus nec, enim. Pellentesque egestas ultricies leo. Proin massa.</p>
	</div>
</body>
</html>
