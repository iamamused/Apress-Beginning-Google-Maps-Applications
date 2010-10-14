<?php
// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Open the CSV file
$handle = @fopen("uk-postcodes.csv","r");
fgets($handle,1024); // Strip off the header line

if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		$line = explode(",",$buffer);
		if (count($line) == 5) {
			$result = mysql_query("INSERT INTO uk_postcodes (outcode,latitude,longitude)
				VALUES ('$line[0]','$line[3]','$line[4]')");
			If (!$result) die ('Error, insert postcode failed: '.mysql_error());
		}
	}
	fclose($handle);
}
?>