<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Client Geocoder Demo</title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $api_key ?>" type="text/javascript"></script>
	<script src="map_functions.js" type="text/javascript"></script>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<!--[if IE]>
	<style type="text/css"> v\:* { behavior:url(#default#VML); } </style>
	<![endif]-->
</head>
<body class="sidebar-right geocoder-idle">
	<div id="toolbar">
		<h1>Lengths and Areas</h1>
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
			<div id="line-info">
				<p><span id="length-title">Length</span>: <span id="length-data">0</span> km</p>
				<p>Area: <span id="area-data">0</span> km<sup>2</sup></p>
			</div>
			<form id="address-search" method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>">
				<p>
					<input type="text" id="s" name="s" />
					<input type="submit" id="submit" value="Add" />
				</p>
				<p id="working">Working ...</p>
			</form>
			
			<ul id="sidebar-list">
			</ul>
		</div>
	</div>
</body>
</html>
