<?php

//This script may require additional memory and time
set_time_limit(0);
ini_set('memory_limit',8388608*10);

//retrieve the variables from the GET vars
list($nelat,$nelng) = explode(',',$_GET['ne']);
list($swlat,$swlng) = explode(',',$_GET['sw']);
list($neX,$neY) = explode(',',$_GET['nePixels']);
list($swX,$swY) = explode(',',$_GET['swPixels']);

//clean the data
$nelng=(float)$nelng;
$swlng=(float)$swlng;
$nelat=(float)$nelat;
$swlat=(float)$swlat;
$w = (int)abs($neX - $swX);
$h = (int)abs($neY - $swY);

$z = (int)$_GET['z'];

//connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

/*
* Retrieve the points within the broundry of the map.
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
		longitude as lng,latitude as lat,struc_height,struc_elevation
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
	fcc_location.longitude as lng,fcc_location.latitude as lat,fcc_structure.struc_height,fcc_structure.struc_elevation
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

$count = mysql_num_rows($result);

//calculate the mercator cordinate position of the top
//latitude and normalize from 0-1
$mercTop = 0.5-(asinh(tan(deg2rad($nelat)))/M_PI/2);

//calculate the scale and y position on the Google Map
$scale = (1 << ($z)) * 256;
$yTop = $mercTop * $scale;

$lngSpan = $nelng-$swlng;
$pixelsPerDegLng = abs($w/$lngSpan);

//create the image
$im = imagecreate($w,$h);
if(!$im) {
	//you could return some sort of error image
	//to provide better feedback to the user
	die();
}
$trans = imagecolorallocate($im,0,0,255);
$black = imagecolorallocate($im,0,0,0);
$white = imagecolorallocate($im,255,255,255);
imagefill($im,0,0,$trans);
imagecolortransparent($im, $trans);

//label the number of points for testing
imagestring($im,1,0,0,$count.' points in this area:',$black);

$row = mysql_fetch_assoc($result);
while($row)
{
	extract($row);

	$lng = $row['lng'];
	$lat = $row['lat'];
	$x = ceil(abs($lng-$swlng) * $pixelsPerDegLng);

	//calculate the mercator cordinate position of this point
	//latitude and normalize from 0-1
	$mercY = 0.5-(asinh(tan(deg2rad($lat)))/M_PI/2);

	//calculate the y position on the Google Map
	$yMap = $mercY * $scale;

	//calculate the y position in the overlay
	$y = $yMap-$yTop;

	//draw the marker, a dot in this case
	imagefilledellipse($im, $x, $y, $z+1, $z+1, $black );
	imageellipse($im, $x, $y, $z+1, $z+1, $white );

	$row = mysql_fetch_assoc($result);
}

//echo a GIF
header('content-type:image/gif;');
imagegif($im);

?>