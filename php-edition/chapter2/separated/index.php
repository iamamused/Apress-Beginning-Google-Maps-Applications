<?php include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; ?>
<!-- [listing 2-2] -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?= $api_key ?>" type="text/javascript"></script>
    <script src="map_data.php" type="text/javascript"></script>
    <script src="map_functions.js" type="text/javascript"></script>
</head>
<body>
    <div id="map" style="width: 500px; height: 300px"></div>
</body>
</html>
<!-- [listing 2-2 end] -->