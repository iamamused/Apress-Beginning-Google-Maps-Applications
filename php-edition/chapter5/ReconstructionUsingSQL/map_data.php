<?php

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);


// Query for using the SQL View 
$query = "SELECT * FROM fcc_towers WHERE struc_state='HI' AND owner_state='HI'";

/* [listing 5-5] */
// Query for the Multi-Table Select
$query = "SELECT * FROM fcc_structure, fcc_owner, fcc_location
	WHERE struc_state='HI' AND owner_state='HI'
	AND unique_si=unique_si_own AND unique_si=unique_si_loc";
/* [listing 5-5 end] */

$result = mysql_query($query, $conn);
$count = 0;

?>

var markers = [
<?php while($row = mysql_fetch_assoc($result)): ?>
	{
		'latitude': <?= $row['latitude'] ?>,
		'longitude': <?= $row['longitude'] ?>,
		'name': '<?= addslashes($row['struc_address']) ?>'
	},
	<?
	$count++;
endwhile; ?>
];
/* Memory used at the end of the script: <? echo memory_get_usage(); ?> */
/* Output <?= $count ?> points */