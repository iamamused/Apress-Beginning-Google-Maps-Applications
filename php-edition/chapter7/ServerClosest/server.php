<?php

//surface distance calculation from Listing 7-3
/* [listing 7-3] */
function surfaceDistance($lat1,$lng1,$lat2,$lng2,$type='km'){
	$a1 =  deg2rad($lat1); //lat 1 in radians
	$a2 = deg2rad($lat2); //lat 2 in radians
	$b1 =  deg2rad($lng1); //lng 1 in radians
	$b2 = deg2rad($lng2); //lng 2 in radians

	// earth radius = 6378.8 kilometers or 3963 miles
	switch(strtolower($type)) {
		case 'km': $r = 6378.8; break; //kilometers
		case 'm': $r = 3963; break; //miles
		case 'n': $r = 3443.9; break; //nautical
	}
	return acos(cos($a1)*cos($b1)*cos($a2)*cos($b2)
	+ cos($a1)*sin($b1)*cos($a2)*sin($b2)
	+ sin($a1)*sin($a2)) * $r;
}
/* [listing 7-3 end] */

//retrieve the variables from the GET vars
list($knownLat,$knownLng) = explode(',',$_GET['known']);
list($nelat,$nelng) = explode(',',$_GET['ne']);
list($swlat,$swlng) = explode(',',$_GET['sw']);

//clean the data
$knownLat=(float)$knownLat;
$knownLng=(float)$knownLng;
$nelng=(float)$nelng;
$swlng=(float)$swlng;
$nelat=(float)$nelat;
$swlat=(float)$swlat;

//connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

/*
* Retrieve the points within the boundary of the map.
* For the FCC data, all the points are within the US so we
* don't need to worry about the meridian problem.
*
* NOTE: For purposes of the on-line example we've also limited
* the data to only towers in Hawaii
*/

/*
//using a view
$result = mysql_query(
	"SELECT
		longitude as lng,latitude as lat
	FROM
		fcc_towers
	WHERE
		struc_state='HI' AND
		(longitude > $swlng AND longitude < $nelng)
		AND (latitude <= $nelat AND latitude >= $swlat)
	ORDER BY
	lat");
*/

//using joins...
$result = mysql_query(
"SELECT
	fcc_location.longitude as lng,fcc_location.latitude as lat
FROM
	fcc_structure, fcc_owner, fcc_location
WHERE
	unique_si=unique_si_own
	AND unique_si=unique_si_loc
	AND struc_state='HI'
	AND (longitude > $swlng AND longitude < $nelng)
	AND (latitude <= $nelat AND latitude >= $swlat)
ORDER BY
	lat"
, $conn);

$list = $distanceList = array();
$i=0;
$row = mysql_fetch_assoc($result);
while($row)
{
	$i++;
	extract($row);

	$list[$i] = "p{$i}:{lat:{$lat},lng:{$lng}}";
	$distanceList[$i] =  surfaceDistance($lat,$lng,$knownLat,$knownLng,'km');
	$row = mysql_fetch_assoc($result);
}

//sort the arrays by distance
array_multisort($distanceList,$list);

//free the distance list
unset($distanceList);

//slice the array to the desired number of points
//20 in this case
$list = array_slice($list,0,20);

//echo back the JavaScript object
header('content-type:text/plain;');
echo "var points = {\n\t".join(",\n\t",$list)."\n}";
?>