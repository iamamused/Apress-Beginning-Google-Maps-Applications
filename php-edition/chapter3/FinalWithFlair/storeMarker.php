<?php
header('Content-Type: text/xml;charset=UTF-8');

//see Listing 3-9 for icon related items in the code

$lat = (float)$_GET['lat'];
$lng = (float)$_GET['lng'];
$found = htmlspecialchars(strip_tags(utf8_encode($_GET['found'])));
$left = htmlspecialchars(strip_tags(utf8_encode($_GET['left'])));
$icon = htmlspecialchars(strip_tags(utf8_encode($_GET['icon'])));

//Create an XML node
$marker = <<<MARKER
<marker lat="$lat" lng="$lng" found="$found" left="$left" icon="$icon"/>
MARKER;

//open the data.xml file for appending
$f=@fopen('data.xml', 'a+');
if(!$f) die('<?xml version="1.0"?>
<response type="error"><![CDATA[Could not open data.xml file]]></response>
');

//add the node
$w=@fwrite($f, $marker);
if(!$w) die('<?xml version="1.0"?>
<response type="error"><![CDATA[Could not write to data.xml file]]></response>');

@fclose($f);

//return a response
$newMarkerContent = "<div><b>Found</b> $found</div><div><b>Left</b> $left</div>";
echo <<<XML
<?xml version="1.0"?>
<response type="success" icon="$icon"><![CDATA[$newMarkerContent]]></response>
XML;
?>
