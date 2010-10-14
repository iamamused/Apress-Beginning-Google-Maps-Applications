<?php
/******** REMEMEBR THIS IS THE COUNTER EXAMPLE (A BAD IDEA) *********/

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Create our temporary holding arrays
$hawaiian_towers = array();
$usi_list = array();

// Get a list of the structures in Hawaii
$structures = mysql_query("SELECT * FROM fcc_structure WHERE struc_state='HI'");
for($i=0; $i<mysql_num_rows($structures); $i++) {
	$row = mysql_fetch_array($structures, MYSQL_ASSOC);
	$hawaiian_towers[$row['unique_si']] = $row;
	$usi_list[] = $row['unique_si'];
}
unset($structures);

// Get all of the owners for the above structures
$owners = mysql_query("SELECT * FROM fcc_owner WHERE unique_si_own IN (".implode(",",$usi_list).")");
for($i=0; $i<mysql_num_rows($owners); $i++) {
	$row = mysql_fetch_array($owners, MYSQL_ASSOC);
	$hawaiian_towers[$row['unique_si_own']] =
	array_merge($hawaiian_towers[$row['unique_si_own']],$row);
}
unset($owners);

// Figure out the location of each of the above structures
$locations = mysql_query("SELECT * FROM fcc_location WHERE unique_si_loc IN (".implode(",",$usi_list).")");
for($i=0; $i<mysql_num_rows($locations); $i++) {
	$row = mysql_fetch_array($locations,MYSQL_ASSOC);
	$hawaiian_towers[$row['unique_si_loc']] =
	array_merge($hawaiian_towers[$row['unique_si_loc']],$row);
}
unset($locations);

echo memory_get_usage();
?>