<?php

header('content-type:text/plain;');

// Our $api_key
include $_SERVER['DOCUMENT_ROOT'] . '/apikey.php'; 

// Create a CURL object for later use
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Open the ronjons.xml file
$datafile = simplexml_load_file("ronjons.xml");
if (!$datafile) die("Unable to open input file!");

foreach ($datafile->store as $store) {
	// Prevent gettting banned 
	// (see: http://googlemapsbook.com/2006/06/21/warning-googles-geocoder/)
	usleep(1750000);
	
	// Construct the geocoder request string (URL)
	$url = "http://maps.google.com/maps/geo?output=xml&key=$api_key&q=";
	$q = $store->address.", ".$store->city.", ".$store->state.", ".$store->zip;
	$url .= urlencode($q);
	echo "\nStore: {$store->name}\n";
	echo "Source Address: $q\n";
	
	// Query Google for this store's longitude and latitude
	curl_setopt($ch, CURLOPT_URL, $url);
	$response = curl_exec($ch);
	
	// Use SimpleXML to parse our answer into something we can use
	$googleresult = simplexml_load_string($response);
	echo "Status: ".$googleresult->Response->Status->code."\n";
	if ($googleresult->Response->Status->code != 200)
	echo "Unable to parse Google response for {$store->name}!\n";
	
	else foreach ($googleresult->Response as $response) {
		foreach ($response->Placemark as $place) {
			list($longitude,$latitude) = split(",",$place->Point->coordinates);
			echo "Result Address: ".$place->address."\n";
			echo " Latitude: $latitude\n";
			echo " Longitude: $longitude\n";
		} // for each placemark
	} // for each Google result
} // for each store
// Close the CURL file and destroy the object

curl_close($ch);
?>