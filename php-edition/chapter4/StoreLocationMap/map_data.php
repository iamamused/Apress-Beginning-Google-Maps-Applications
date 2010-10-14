<?php
// Open the ronjons_cache.xml file and load the data for the pins
$datafile = simplexml_load_file("ronjons_cache.xml");
echo "var markers = [\n";
foreach ($datafile->store as $store) {
	$description = "{$store->address}<br />";
	if ($store->address2) $description .= "{$store->address2}<br/>";
	$description .= "{$store->city}, {$store->state}<br/>";
	$description .= "{$store->zip}<br/>";
	$description .= "Phone: {$store->phone}<br/>";
	echo "{
	  'latitude': {$store->latitude},
	  'longitude': {$store->longitude},
	  'name': '{$store->name}',
	  'description': '$description'
	},\n";
}
echo "];\n";
?>