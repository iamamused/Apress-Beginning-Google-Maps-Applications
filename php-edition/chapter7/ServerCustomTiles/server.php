<?php
//include the helper calculations
require('GoogleMapUtility.php');

//this script may require additional memory and time
set_time_limit(0);
ini_set('memory_limit',8388608*10);

//create an array of the size for each marker at each zoom level
$markerSizes = array(1,1,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12);

//get the lat/lng bounds of this tile from the utility function
//return abounds object with width,height,x,y
$rect = GoogleMapUtility::getTileRect(
	(int)$_GET['x'],
	(int)$_GET['y'],
	(int)$_GET['zoom']
);

//create aunique file name for this tile
$file = 'tiles/c'.md5(
	serialize($markerSizes).
	serialize($rect).'|'.
	$_GET['x'].'|'.
	$_GET['y'].'|'.
	$_GET['zoom']).'.gif';

//check if the file already exists
if(!file_exists($file)) {

	//create anew image
	$im = imagecreate(GoogleMapUtility::TILE_SIZE,GoogleMapUtility::TILE_SIZE);
	$trans = imagecolorallocate($im,0,0,255);
	imagefill($im,0,0,$trans);
	imagecolortransparent($im, $trans);
	$black = imagecolorallocate($im,0,0,0);
	$white = imagecolorallocate($im,255,255,255);

	//set up some colors for the markers.
	//each marker will have acolor based on the height of the tower
	$darkRed = imagecolorallocate($im,150,0,0);
	$red = imagecolorallocate($im,250,0,0);
	$darkGreen = imagecolorallocate($im,0,150,0);
	$green = imagecolorallocate($im,0,250,0);
	$darkBlue = imagecolorallocate($im,0,0,150);
	$blue = imagecolorallocate($im,0,0,250);
	$orange = imagecolorallocate($im,250,150,0);

	//init some vars
	$extend = 0;
	$z = (int)$_GET['zoom'];
	$swlat=$rect->y + $extend;
	$swlng=$rect->x+ $extend;
	$nelat=$swlat+$rect->height + $extend;
	$nelng=$swlng+$rect->width + $extend;

	//connect to the database
	require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
	$conn = mysql_connect("localhost", $db_name, $db_pass);
	mysql_select_db("googlemapsbook", $conn);

	/*
	* Retrieve the points within the boundary of the map.
	* For the FCC data, all the points are within the US so we
	* don't need to worry about the meridian problem.
	*/

	/*
	//using a view...
	$result = mysql_query(
	"SELECT
		longitude as lng,latitude as lat,struc_height,struc_elevation
	FROM
		fcc_towers
	WHERE
		(longitude > $swlng AND longitude < $nelng)
	AND (latitude <= $nelat AND latitude >= $swlat)
		ORDER BY
	lat"
	, $conn);
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
		AND (longitude > $swlng AND longitude < $nelng)
		AND (latitude <= $nelat AND latitude >= $swlat)
	ORDER BY
		lat"
	, $conn);

	//get the number of points in this tile
	$count = mysql_num_rows($result);
	$filled=array();
	if($count>0) {
		$row = mysql_fetch_assoc($result);
		while($row)
		{
			//get the x,y coordinate of the marker in the tile
			$point = GoogleMapUtility::getPixelOffsetInTile($row['lat'],$row['lng'],$z);

			//check if the marker was already drawn there
			if($filled["{$point->x},{$point->y}"]<2) {

				//pick acolor based on the structure's height
				if($row['struc_height']<=20) $c = $darkRed;
				elseif($row['struc_height']<=40) $c = $red;
				elseif($row['struc_height']<=80) $c = $darkGreen;
				elseif($row['struc_height']<=120) $c = $green;
				elseif($row['struc_height']<=200) $c = $darkBlue;
				else $c = $blue;

				//if there is aready apoint there, make it orange
				if($filled["{$point->x},{$point->y}"]==1) $c=$orange;

				//get the size
				$size = $markerSizes[$z];

				//draw the marker
				if($z<2) imagesetpixel($im, $point->x, $point->y, $c );
				elseif($z<12) {
					imagefilledellipse($im, $point->x, $point->y, $size, $size, $c );
					imageellipse($im, $point->x, $point->y, $size, $size, $white );
				} else {
					imageellipse($im, $point->x, $point->y, $size-1, $size-1, $c );
					imageellipse($im, $point->x, $point->y, $size-2, $size-2, $c );
					imageellipse($im, $point->x, $point->y, $size+1, $size+1, $black );
					imageellipse($im, $point->x, $point->y, $size, $size, $white );
				}

				//record that we drew the marker
				$filled["{$point->x},{$point->y}"]++;
			}
			$row = mysql_fetch_assoc($result);
		}
	}
	//write some info about the tile to the image for testing
	imagestring($im,1,-1,0, "$count points in tile ({$_GET['x']},{$_GET['y']}) @ zoom $z ",$white);
	imagestring($im,1,0,1, "$count points in tile ({$_GET['x']},{$_GET['y']}) @ zoom $z ",$white);
	imagestring($im,1,0,-1, "$count points in tile ({$_GET['x']},{$_GET['y']}) @ zoom $z ",$white);
	imagestring($im,1,1,0,	"$count points in tile ({$_GET['x']},{$_GET['y']}) @ zoom $z ",$white);
	imagestring($im,1,0,0,	"$count points in tile ({$_GET['x']},{$_GET['y']}) @ zoom $z ",$black);
	imagestring($im,1,0,9, date('r'),$black);

	//output the new image to the file system and then send it to the browser
	header('content-type:image/gif;');
	imagegif($im,$file);
	echo file_get_contents($file);

} else {
	//output the existing image to the browser
	header('content-type:image/gif;');
	echo file_get_contents($file);
}
?>