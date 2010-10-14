<?php

$chapter_meta = array(
	'number' => 7,
	'title' => 'Optimizing and Scaling for Large Data Sets',
	'summary' => ''
);


$examples = array(
	'ServerBounds' => 'Server Side Boundary Method',
	'ServerClosest' => 'Server Side Closest to Common Point Method',
	'ServerCluster' => 'Server Side Clustering Method',
	'ServerCustomOverlay' => 'Custom Detail Overlay Method',
	'ServerCustomTiles' => 'Custom Tile Method',
	'ClientBounds' => 'Client Side Boundary Method',
	'ClientClosest' => 'Client Side Closest to Common Point Method',
	'ClientCluster' => 'Client Side Clustering Method',
	'TrackingPoints' => 'Keeping Track of point to eliminate &quot;flashing&quot; while loading'
);

$code_listings = array(
	'01' => '/chapter7/ServerBounds/map_functions.js.source',
	'02' => '/chapter7/ServerBounds/server.php.source',

	'03' => '/chapter7/ServerClosest/server.php.source#l7-3',
	'04' => '/chapter7/ServerClosest/map_functions.js.source',
	'05' => '/chapter7/ServerClosest/server.php.source',

	'06' => '/chapter7/ServerCluster/map_functions.js.source',
	'07' => '/chapter7/ServerCluster/server.php.source',

	'08' => '/chapter7/GoogleRectangle.js.source',
	'09' => '/chapter7/ServerCustomOverlay/map_functions.js.source',
	'10' => '/chapter7/ServerCustomOverlay/server.php.source',

	'11' => '/chapter7/ServerCustomTiles/GoogleMapUtility.php.source',
	'12' => '/chapter7/ServerCustomTiles/map_functions.js.source',
	'13' => '/chapter7/ServerCustomTiles/server.php.source',

	'14' => '/chapter7/ClientBounds/map_functions.js.source',

	'15' => '/chapter7/ClientClosest/map_functions.js.source',

	'16' => '/chapter7/ClientCluster/map_functions.js.source',

	'17' => '/chapter7/TrackingPoints/map_functions.js.source'
);

$other_links = array(
	'/chapter7/capital_cities_seed.sql.source' => 'Capital Cities SQL Seed'

);

include('../chapter.php');

?>
