<?php

$chapter_meta = array(
	'number' => 4,
	'title' => 'Geocoding Addresses',
	'summary' => ''
);

$examples = array(
	'GoogleGeocoder' => 'The Google Maps API Geocoder',
	'YahooGeocoder' => 'The Yahoo Geocoding API',
	'USGeocoder' => 'Using Geocoder.us (takes over a minute to execute)',
	'CAGeocoder' => 'Using Geocoder.ca',
	'CachingLookups' => 'Caching Lookups',
	'StoreLocationMap' => 'Building a Store Locations Map',
);

include('../apikey.php');

$other_links = array();

$code_listings = array(
	'01' => '/chapter4/ronjons.xml.source',
	'02' => '/chapter4/GoogleGeocoder/sample_response.xml.source',
	'03' => '/chapter4/GoogleGeocoder/index.php.source',
	'04' => '/chapter4/GoogleGeocoder/result.txt.source',
	'05' => '/chapter4/YahooGeocoder/sample_response.xml.source',
	'06' => '/chapter4/YahooGeocoder/index.php.source',
	'07' => '/chapter4/YahooGeocoder/result.txt.source',
	'08' => '/chapter4/USGeocoder/index.php.source',
	'09' => '/chapter4/USGeocoder/result.txt.source',
	'10' => '/chapter4/CAGeocoder/sample_response.xml.source',
	'11' => '/chapter4/CAGeocoder/index.php.source',
	'12' => '/chapter4/CAGeocoder/result.txt.source',
	'13' => '/chapter4/CachingLookups/index.php.source',
	'14' => '/chapter4/CachingLookups/ronjons_cache.xml.source',
	'15' => '/chapter4/StoreLocationMap/map_data.php.source',
	'16' => '/chapter4/StoreLocationMap/map_data.js.source',
	'17' => '/chapter4/StoreLocationMap/map_functions.js.source',
);

$further_reading = array(
	'http://www.ronjons.com' => 'Ron Jon Surf Shops websit',
	'http://www.xml.com/pub/a/1999/01/namespaces.html' => 'More information about XML namespace',
	'http://googlemapsbook.com/geocoders' => 'Our resource for API-based Geocoder',
	'http://www.php.net/curl' => 'More infotmation on the CURL PHP extension',
	'http://www.zend.com/php5/articles/php5-simplexml.php' => 'Zend\'s Simple XML Primer',
	'http://minixml.psychogenic.com' => 'MiniXML',
	'http://www.google.com/apis/maps/documentation/#Geocoding_Examples' => 'Official Google Maps API documentation -- Geocoder',
	'http://googlemapsapi.blogspot.com/' => 'Official Google Maps API blog',
	'http://www.oasis-open.org/committees/ciq/ciq.html#6' => 'The xAL Specification',
	'http://developer.yahoo.net/maps/rest/V1/geocode.html' => 'Yahoo Geocoder API Spcification & Documentation',
	'http://api.search.yahoo.com/webservices/register_application' => 'Application for a Yahoo Developer Code',
	'http://developer.yahoo.net/search/rate.html' => 'Yahoo Rate Limiting explanation',
	'http://www.geonames.org/export/geonames-search.html' => 'GeoNames.org Full Text Database Search',
	'http://www.viamichelin.com' => 'ViaMichelin - Fee-based Geocoder for Europe',
);

include('../chapter.php');

?>
