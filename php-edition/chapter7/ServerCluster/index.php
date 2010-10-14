<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
     <script src="http://maps.google.com/maps?file=api&v=2&key=<?= $api_key ?>" type="text/javascript"></script>
     <script src="map_data.php" type="text/javascript"></script>
     <script src="map_functions.js" type="text/javascript"></script>
     <link href="style.css" rel="stylesheet" type="text/css" />
     <!--[if IE]><style type="text/css">v\:*{behavior:url(#default#VML);}</style><![endif]-->
</head>
<body>
	<div id="map"></div>
</body>
</html>
