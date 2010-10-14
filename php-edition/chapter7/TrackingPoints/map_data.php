<?php
//Using Capital Cities as an example...

//connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

//retrieve all points
$result = mysql_query(
"SELECT
	lat,lng,capital,country
FROM
	capital_cities
ORDER BY
lat"
, $conn);

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