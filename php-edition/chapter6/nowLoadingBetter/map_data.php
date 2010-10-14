<?php

require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');

// Connect to the database
$conn = mysql_connect($db_host, $db_name, $db_pass);
mysql_select_db("geocoder_experiment", $conn);

$north = 20.421865;
$south = 18.281518;
$west = -156.123962;
$east = -150;

$query = "SELECT * FROM fcc_towers 
WHERE longitude < $east
  AND latitude < $north
  AND longitude > $west
  AND latitude > $south";

$result = mysql_query($query, $conn);
$joiner = '';
$count = 0;
?>

[
<?php while($row = mysql_fetch_assoc($result)): ?>
	<?= $joiner ?>
	{
		'latitude': <?= $row['latitude'] ?>,
		'longitude': <?= $row['longitude'] ?>,
		'address': '<?= addslashes($row['struc_address']) ?>',
		'city': '<?= addslashes($row['struc_city']) ?>',
		'state': '<?= addslashes($row['struc_state']) ?>',
		'height': '<?= addslashes($row['struc_height']) ?>',
		'elevation': '<?= addslashes($row['struc_elevation']) ?>',
		'type': '<?= addslashes($row['struc_type']) ?>',
		'owner': '<?= addslashes($row['owner_name']) ?>'
	}
	<?
		$joiner = ',';
		$count++;
	?>
<?php endwhile; ?>
];

/* Output <?= $count ?> points */
