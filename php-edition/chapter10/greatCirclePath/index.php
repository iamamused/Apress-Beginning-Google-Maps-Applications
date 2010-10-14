<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Great Circle Demo</title>
	<!-- This has to use Version 1 of the API in order to work with XMaps -->
	<script src="http://maps.google.com/maps?file=api&amp;v=1&amp;key=<?= $api_key ?>" type="text/javascript"></script>
	<script src="xmaps.1c.js" type="text/javascript"></script>
	<script src="map_functions.js" type="text/javascript"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body class="sidebar-right">
	<div id="toolbar">
		<h1>Great-Circle Path</h1>
		<ul id="options">
			<li><a href="/chapter10/">Back to Chapter 10</a></li>
		</ul>
		<ul id="sidebar-controls">
			<li><a href="#" id="button-sidebar-hide">hide</a></li>
			<li><a href="#" id="button-sidebar-show">show</a></li>
		</ul>		
	</div>
	<div id="content">
		<div id="map-wrapper">
			<div id="map"></div>
		</div>
		<div id="sidebar">
			<p>This map uses Chris Smoak's <a href="http://xmaps.busmonster.com/">XMaps library</a> to trace a great-circle path from New York to Paris.</p>
		</div>
	</div>
</body>
</html>
