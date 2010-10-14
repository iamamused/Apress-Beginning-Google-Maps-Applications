<?php
//using FCC tower data as an example...

$nelng=-71.751708984375;
$swlng=-73.39965820312499;
$nelat=42.589488572714245;
$swlat=41.57025176609894;

//connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

//retrieve all points
$result = mysql_query("
	SELECT
		longitude,latitude,struc_height,struc_elevation
	FROM
		fcc_towers
	WHERE
		(longitude > $swlng AND longitude < $nelng)
		AND (latitude < $nelat AND latitude > $swlat)
	ORDER BY
		struc_height
", $conn);

$list = array();
$i=0;
$row = mysql_fetch_assoc($result);
while($row)
{
	$i++;
	extract($row);
	$list[] = "p{$i}:{lat:{$latitude},lng:{$longitude}}";
	$row = mysql_fetch_assoc($result);
}

//echo back the JavaScript object nicely formatted
header('content-type:text/plain;');
echo "var points = {\n\t".join(",\n\t",$list)."\n}";
?>