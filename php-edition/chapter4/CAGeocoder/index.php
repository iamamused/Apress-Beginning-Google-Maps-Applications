<?php
header('content-type:text/plain;');

// Address to geocode (the CN Tower)
$street_no = "301";
$street = "Front Street West";
$city = "Toronto";
$prov = "ON";
$postal = "M5V2T6";

// Create a CURL object for later use
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Construct the request string
$url = "http://geocoder.ca/?";
$url .= "&stno=".urlencode($street_no);
$url .= "&addresst=".urlencode($street);
$url .= "&city=".urlencode($city);
$url .= "&prov=".$prov;
$url .= "&postal=".$postal;
$url .= "&geoit=XML";

// Query Geocoder.ca for the lat/long
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);

// Use SimpleXML to parse our answer into something we can use
$resultset = simplexml_load_string($response);
if (!$resultset) die("Unable to parse the response!");
echo "The CN tower is located here:\n";
echo "Latitude: {$resultset->latt}\n";
echo "Longitude: {$resultset->longt}\n";

// Close the CURL file and destroy the object
curl_close($ch);
?>