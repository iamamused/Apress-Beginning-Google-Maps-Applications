<?php

// Your Yahoo! Application Code
$appid = "yahoo_demo";

// Create a CURL object for later use
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Open the ronjons.xml file
$datafile = simplexml_load_file("ronjons.xml");

// Open a file to store our consolidated information in
$newfile = fopen("ronjons_cache.xml", "w+");
fputs($newfile,'<?xml version="1.0" encoding="UTF-8"?>'."\n");
fputs($newfile,'<stores>'."\n");

foreach ($datafile->store as $store) {
	// Construct the request string
	$url = "http://api.local.yahoo.com/MapsService/V1/geocode?appid=$appid";
	if ($store->address) $url .= "&street=".urlencode($store->address);
	if ($store->city) $url .= "&city=".urlencode($store->city);
	if ($store->state) $url .= "&state=".urlencode($store->state);
	if ($store->zip) $url .= "&zip=".trim($store->zip);
	
	// Query Yahoo for this store's lat/long
	curl_setopt($ch, CURLOPT_URL, $url);
	$response = curl_exec($ch);
	
	// Use SimpleXML to parse our answer into something we can use
	$yahooresult = simplexml_load_string($response);
	foreach ($yahooresult->Result as $result) {
		$latitude = $result->Latitude;
		$longitude = $result->Longitude;
	} // for each Yahoo Result
	
	// Lastly output the XML to our file
	fputs($newfile,' <store>'."\n");
	fputs($newfile,' <name>'.trim($store->name).'</name>'."\n");
	fputs($newfile,' <address>'.trim($store->address).'</address>'."\n");
	if ($store->address2)
		fputs($newfile,' <address2>'.trim($store->address2).'</address2>'."\n");
	fputs($newfile,' <city>'.trim($store->city).'</city>'."\n");
	fputs($newfile,' <state>'.trim($store->state).'</state>'."\n");
	fputs($newfile,' <zip>'.trim($store->zip).'</zip>'."\n");
	fputs($newfile,' <phone>'.trim($store->phone).'</phone>'."\n");
	fputs($newfile,' <pin>'.trim($store->pin).'</pin>'."\n");
	fputs($newfile,' <latitude>'.trim($latitude).'</latitude>'."\n");
	fputs($newfile,' <longitude>'.trim($longitude).'</longitude>'."\n");
	fputs($newfile,' </store>'."\n");
} // for each store

// Close the CURL file and destroy the object
curl_close($ch);

// Close the new file freeing the memory
fputs($newfile,'</stores>'."\n");
fclose($newfile);

header("Location: ronjons_cache.xml");
?>