<?php

header('content-type:text/plain;');

// Your Yahoo! Application id
$appid = "yahoo_demo";

// Create a CURL object for later use
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Open the ronjons.xml file
$datafile = simplexml_load_file("ronjons.xml");
if (!$datafile) die("Unable to open input file!");

foreach ($datafile->store as $store) {
	// Construct the request string
	$url = "http://api.local.yahoo.com/MapsService/V1/geocode?appid=$appid";
	if ($store->address) $url .= "&street=".urlencode($store->address);
	if ($store->city) $url .= "&city=".urlencode($store->city);
	if ($store->state) $url .= "&state=".urlencode($store->state);
	if ($store->zip) $url .= "&zip=".$store->zip;
	echo "Store: {$store->name}\n";
	
	// Query Yahoo for this store's lat/long
	curl_setopt($ch, CURLOPT_URL, $url);
	$response = curl_exec($ch);
	
	// Use SimpleXML to parse our answer into something we can use
	$yahooresult = simplexml_load_string($response);
	if (!$yahooresult) echo "Unable to parse Yahoo response for {$store->name}!\n";
	else foreach ($yahooresult->Result as $result) {
		echo "Result Precision: {$result['precision']}\n";
		if ($result['precision'] != "address") {
			echo "Warning: {$result['warning']}\n";
			echo "Address: {$result->Address}\n";
		}
		echo "Latitude: {$result->Latitude}\n";
		echo "Longitude: {$result->Longitude}\n\n";
	} // for each Yahoo result
} // for each store

// Close the CURL file and destroy the object
curl_close($ch);
?>