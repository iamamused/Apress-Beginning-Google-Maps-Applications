<?php
// Start our response
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><ResultSet>';

// Clean up the request and make sure it's not longer than four characters
$code = trim($_REQUEST['code']);
$code = preg_replace("/[^a-z0-9]/i","",$code);
$code = strtoupper($code);
$code = substr($code,0,4);

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Look up the provided code
$result = mysql_query("SELECT * FROM uk_postcodes WHERE outcode = '$code'");
if (!$result || mysql_num_rows($result) == 0)
die("<Error>No Matches</Error></ResultSet>");

// Output the match that was found
$row = mysql_fetch_array($result,MYSQL_ASSOC);
echo "<Result>
<Latitude>{$row['latitude']}</Latitude>
<Longitude>{$row['longitude']}</Longitude>
<OutCode>{$row['outcode']}</OutCode>
</Result>";

// Close our response
echo "</ResultSet>";
?>