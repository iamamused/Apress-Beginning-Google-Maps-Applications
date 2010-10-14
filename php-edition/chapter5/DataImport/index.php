<?php

set_time_limit(0); // this could take a while

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Open the Physical Location Coordinates file
$handle = @fopen("../data/RA.dat","r");
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		$row = explode("|",$buffer);
		if ($row[3] > 0) {
			// Modify things before we insert them
			$row[12] = date("Y-m-d",strtotime($row[12]));
			$row[13] = date("Y-m-d",strtotime($row[13]));
			$row[23] = addslashes($row[23]);
			$row[24] = addslashes($row[24]);
			$row[30] = addslashes($row[30]);
			
			// Formulate our query
			$query = "INSERT INTO fcc_structure (unique_si, date_constr,
				date_removed, struc_address, struc_city, struc_state, struc_height,
				struc_elevation, struc_ohag, struc_ohamsl, struc_type)
				VALUES ({$row[4]}, '{$row[12]}', '{$row[13]}', '{$row[23]}',
				'{$row[24]}', '{$row[25]}', '{$row[26]}', '{$row[27]}', '{$row[28]}',
				'{$row[29]}', '{$row[30]}')";
			
			// Execute our query
			$result = @mysql_query($query);
			if (!$result) echo("ERROR: Duplicate structure info #{$row[4]} <br>\n");
		}
	}
	fclose($handle);
}

echo "Done Structures. <br>\n";

// Open the Ownership Data file
$handle = @fopen("../data/EN.dat","r");
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		$row = explode("|",$buffer);
		if ($row[3] > 0) {
			$row[7] = addslashes($row[7]);
			$row[14] = addslashes($row[14]);
			$row[16] = addslashes($row[16]);
			$query = "INSERT INTO fcc_owner (unique_si_own, owner_name,
				owner_address, owner_city, owner_state, owner_zip) VALUES ({$row[4]},
				'{$row[7]}', '{$row[14]}','{$row[16]}', '{$row[17]}', '{$row[18]}')";
			$result = @mysql_query($query);
			if (!$result) {
				// Newer information later in the file: UPDATE instead
				$query = "UPDATE fcc_owner SET owner_name='{$row[7]}',
					owner_address='{$row[14]}', owner_city='{$row[16]}',
					owner_state='{$row[17]}', owner_zip='{$row[18]}'
					WHERE unique_si_own={$row[4]}";
				$result = @mysql_query($query);
				if (!$result)
					echo "Failure to import ownership for struc. #{$row[4]}<br>\n";
				else
					echo "Updated ownership for struc. #{$row[4]} <br>\n";
			}
		}
	}
	fclose($handle);
}

echo "Done Ownership. <br>\n";

// Open the Physical Locations file
$handle = @fopen("../data/CO.dat","r");
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 4096);
		$row = explode("|",$buffer);
		if ($row[3] > 0) {
			if ($row[9] == "S") $sign = -1; else $sign = 1;
			$dec_lat = $sign*($row[6]+$row[7]/60+$row[8]/3600);
			if ($row[14] == "W") $sign = -1; else $sign = 1;
			$dec_long = $sign*($row[11]+$row[12]/60+$row[13]/3600);
			$query = "INSERT INTO fcc_location (unique_si_loc, lat_deg, lat_min,
				lat_sec, lat_dir, latitude, long_deg, long_min, long_sec,
				long_dir, longitude) VALUES ({$row[4]},'{$row[6]}', '{$row[7]}',
				'{$row[8]}', '{$row[9]}', '$dec_lat','{$row[11]}', '{$row[12]}',
				'{$row[13]}', '{$row[14]}', '$dec_long')";
			$result = @mysql_query($query);
			if (!$result) {
				// Newer information later in the file: UPDATE instead
				$query = "UPDATE fcc_location SET lat_deg='{$row[6]}',
					lat_min='{$row[7]}', lat_deg='{$row[8]}', lat_dir='{$row[9]}',
					latitude='$dec_lat', long_deg='{$row[11]}', long_min='{$row[12]}',
					long_sec='{$row[13]}', long_dir='{$row[14]}', longitude='$dec_long'
					WHERE unique_si_loc='{$row[4]}'";
				$result = @mysql_query($query);
				if (!$result)
					echo "Failure to import location for struc. #{$row[4]} <br>\n";
				else
					echo "Updated location for struc. #{$row[4]} <br>\n";
			}
		}
	}
	fclose($handle);
}

echo "Done Locations. <br>\n";

?>