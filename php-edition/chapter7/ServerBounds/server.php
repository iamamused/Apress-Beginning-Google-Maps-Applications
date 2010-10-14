<?php
//retrieve the variables from the GET vars
list($nelat,$nelng) = explode(',',$_GET['ne']);
list($swlat,$swlng) = explode(',',$_GET['sw']);

//clean the data
$nelng=(float)$nelng;
$swlng=(float)$swlng;
$nelat=(float)$nelat;
$swlat=(float)$swlat;

//connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

if($nelng > $swlng) {
	//retrieve all points in the southwest/northeast boundary
	$result = mysql_query(
	"SELECT
		lat,lng,capital,country
	FROM
		capital_cities
	WHERE
		(lng > $swlng AND lng < $nelng)
	AND (lat <= $nelat AND lat >= $swlat)
		ORDER BY
	lat"
	, $conn);

} else {
	//retrieve all points in the southwest/northeast boundary
	//split over the meridian
	$result = mysql_query(
	"SELECT
		lat,lng,capital,country
	FROM
		capital_cities
	WHERE
		(lng >= $swlng OR lng <= $nelng)
		AND (lat <= $nelat AND lat >= $swlat)
	ORDER BY
		lat"
	, $conn);
}

$list = array();
$i=0;
$row = mysql_fetch_assoc($result);
while($row)
{
	$i++;
	extract($row);
	$city = addcslashes($capital.', '.$country,"'");
	$list[] = "p{$i}:{lat:{$lat},lng:{$lng},city:'{$city}'}";
	$row = mysql_fetch_assoc($result);
}
//echo back the JavaScript object nicely formatted
header('content-type:text/plain;');
echo "var points = {\n\t".join(",\n\t",$list)."\n}";
?>