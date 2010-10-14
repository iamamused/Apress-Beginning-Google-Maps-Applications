<?php
// Open the file and the database
$handle = @fopen("scrape_me.html","r");
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Status flags and temporary variables
$in_main_table = false;
$count = 0;
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		// Look for "<!-- Content Body -->"
		if (trim($buffer) == "<!-- Content Body -->") {
			$in_main_table = true;
			continue;
		}
		
		// For each line that has "latlongtable" in it trim
		if ($in_main_table && strstr($buffer,'class="latlongtable"') !== false) {
			// Dig out the part we care about
			$interesting_data = trim(strip_tags($buffer));
			switch($count % 4) {
				case 0:
					// Country Info
					$city = array(); // reset
					$city[0] = addslashes($interesting_data);
					break;
				case 1:
					// Capital City Info
					$city[1] = addslashes($interesting_data);
					break;
				case 2:
					// Latitude Information (determine sign)
					$latitude = substr($interesting_data,0,strlen($interesting_data)-1);
					if (substr($interesting_data,-1,1) == 'S') $sign = "-";
					else $sign = "";
					$city[2] = $sign.$latitude;
					break;
				case 3:
					//Longitude Information (determine sign)
					$longitude = substr($interesting_data,0,strlen($interesting_data)-1);
					if (substr($interesting_data,-1,1) == 'W') $sign = "-";
					else $sign = "";
					$city[3] = $sign.$longitude;
					echo implode(" ",$city)."<br />";
					
					// Write to the database
					$result = mysql_query("INSERT INTO capital_cities (country,capital,lat,lng) VALUES ('".implode("','",$city)."')");
					break;
			} // switch
			
			// Increment our counter
			$count++;
			
			// Stop when we find "<!-- Content Body End -->"
			if ($buffer == "<!-- Content Body End -->") break;
		} // if
	} // while
} // if

fclose($handle);
?>