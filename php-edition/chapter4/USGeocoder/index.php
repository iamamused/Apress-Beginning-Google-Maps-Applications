<?php

header('content-type:text/plain;');

// Create a CURL object for later use
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Open the ronjons.xml file
$datafile = simplexml_load_file("ronjons.xml");
if (!$datafile) die("Unable to open input file!");

foreach ($datafile->store as $store) {
	// Construct the request string
	$url = "http://geocoder.us/service/csv/geocode?address=";
	$address = "";
	if ($store->address) $address .= $store->address.", ";
	if ($store->city) $address .= $store->city." ";
	if ($store->state) $address .= $store->state." ";
	if ($store->zip) $address .= $store->zip;
	$url .= urlencode($address);
	echo "Store: {$store->name}\n";
	
	// Query Geocoder.us for this store's lat/long
	curl_setopt($ch, CURLOPT_URL, $url);
	$response = curl_exec($ch);
	
	// Split up the CSV result into components
	list($lat,$long,$address,$city,$state,$zip) = split(",",$response);
	echo "Latitude: $lat\n";
	echo "Longitude: $long\n\n";
} // for each store

// Close the CURL file and destroy the object
curl_close($ch);
?>