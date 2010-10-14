<?php

$chapter_meta = array(
	'number' => 11,
	'title' => 'Advanced Geocoding Topics',
	'summary' => ''
);

$examples = array(
	'UKRest/index.php?code=AB10' => 'UK Postcode-based REST service Example',
	'TIGERRest/tiger_lookup.php?state=California&city=San+Francisco&street=Dolores&number=140' => 'XML response from our home-built Geocoder',
);

$code_listings = array(
	'01' => '/chapter11/uk-postcodes.csv.source',
	'02' => '/chapter11/sql_schema.txt.source#l11-2',
	'03' => '/chapter11/11-3.phps.source',
	'04' => '/chapter11/UKRest/index.php.source',
	'05' => '/chapter11/sql_schema.txt.source#l11-5',
	'06' => '/chapter11/TIGERRest/tiger_helpers.php.source',
	'07' => '/chapter11/TIGERRest/tiger_import.phps.source#l11-7',
	'08' => '/chapter11/TIGERRest/tiger_import.phps.source#l11-8',
	'09' => '/chapter11/TIGERRest/tiger_import.phps.source#l11-9',
	'10' => '/chapter11/TIGERRest/tiger_lookup.php.source',
);


$other_links = array(
	'uk-postcodes.csv' => 'Partial list of UK Postal Codes - Originally from Jibble.org (http://www.jibble.org/ukpostcodes/)',
	'http://www2.census.gov/geo/tiger/tiger2005se/CA/tgr06075.zip' => 'TIGER/Line data for San Francisco California',
	'tiger_dicts.zip' => 'Dictionary Files needed for this chapter',
);

$further_reading = array(
	'http://www.census.gov/geo/www/tiger/index.html' => 'Official website for the US Cencus Bureau\'s TIGER/Line data',
	'http://geodepot.statcan.ca/Diss/Data/Data_e.cfm' => 'Canada\'s Equivalent to the TIGER/Line: The Road Network Files',
	'http://www.statcan.ca/bsolc/english/bsolc?catno=92F0153X' => 'Canada\'s Postal Code Conversion Files',
	'http://www.virginradio.co.uk/vip/map.html' => 'Virgin Radio VIP Members Map',
	'http://www.census.gov/geo/www/tiger/tiger2005se/TGR05SE.pdf' => 'TIGER/Line 2005 SE Official Documentation',
	
);

include('../chapter.php');

?>
