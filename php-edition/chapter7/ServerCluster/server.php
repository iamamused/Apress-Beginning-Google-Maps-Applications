<?php
//This script may require additional memory
ini_set('memory_limit',8388608 * 10);

//retrieve the variables from the GET vars
list($nelat,$nelng) = explode(',',$_GET['ne']);
list($swlat,$swlng) = explode(',',$_GET['sw']);

//clean the data
$nelng = (float)$nelng;
$swlng = (float)$swlng;
$nelat = (float)$nelat;
$swlat = (float)$swlat;

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

//extract all the points from the result into an array
$list = array();
$row = mysql_fetch_assoc($result);
while($row)
{
	//use 'm' to indicate this is aregular (m)arker
	$list[] = array($row['lat'],$row['lng'],'m');
	$row = mysql_fetch_assoc($result);
}

//close the SQL connection
mysql_close($conn);

//limit to 30 markers
$limit = 30;
$gridSize = 0;
$listRemove = array();
while(count($list)>$limit) {

	//grid size in pixels. if the first pass fails to reduce the
	//number of markers below the limit, the grid will increase
	//again and redo the loop.
	$gridSize += ($nelng-$swlng)/30;
	$clustered = array();
	reset($list);

	//loop through the $list and put each one in agrid square
	while(list($k,$v) = each($list)) {

		//calculate the y position based on the latitude: $v[0]
		$y = floor(($v[0]-$swlat)/$gridSize);

		//calculate the x position based on the longitude: $v[1]
		$x = floor(($v[1]-$swlng)/$gridSize);

		//use the x and y values as the key for the array and append
		//the points key to the clustered array
		$clustered["{$x},{$y}"][] = $k;
	}

	//check if we're below the limit and if not loop again
	if(count($clustered)>$limit) continue;

	//reformat the list array
	$listRemove = array();
	while(list($k,$v) = each($clustered)) {

		//only merge if there is more than one marker in acell
		if(count($v)>1) {

			//create alist of the merged markers
			$listRemove = array_merge($listRemove,$v);

			//add acluster marker to the list
			$clusterLat = $list[$v[0]][0];
			$clusterLng = $list[$v[0]][1];

			//use 'c' to indicate this is a(c)luster marker
			$list[] = array($clusterLat,$clusterLng,'c');
		}
	}

	//unset all the merged pins
	//reverse to start with highest key
	rsort($listRemove);
	while(list($k,$v) = each($listRemove)) {
		unset($list[$v]);
	}

	//we're done!
	break;
}

reset($list);

$json = array();
while(list($key,$values) = each($list)) {
	$i++;
	$json[] = "p{$i}:{lat:{$values[0]},lng:{$values[1]},type:'{$values[2]}'}";
}

//echo back the JavaScript object
header('content-type:text/plain;');
echo "var points = {\n\t".join(",\n\t",$json)."\n}";
?>