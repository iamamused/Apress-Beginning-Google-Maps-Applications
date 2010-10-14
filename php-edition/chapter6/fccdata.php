<?

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

$result = mysql_query("SELECT count(*) FROM fcc_towers", $conn);
$row = mysql_fetch_assoc($result);

var_dump($row);

/*$result = mysql_query("UPDATE fcc_towers SET longitude = (- long_deg - (long_min * (1/60)) - (long_sec * (1/3600)))", $conn);
die();*/

$result = mysql_query("SELECT * FROM fcc_towers WHERE longitude < -150 AND latitude < 30", $conn);
$row = mysql_fetch_assoc($result);

echo '<table><thead><tr>';
foreach($row as $title => $content)
	echo '<th>'.$title.'</th>';
echo '</tr></thead><tbody>';	

while($row)
{
	echo '<tr>';
	foreach($row as $title => $content)
		echo '<td>'.$content.'</td>';
	echo '</tr>';
	$row = mysql_fetch_assoc($result);
}


?>
