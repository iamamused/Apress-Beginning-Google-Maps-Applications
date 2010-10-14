<?php
// Open the Registrations and Applications Data file
$handle = @fopen("../data/RA.dat","r");

// Parse and output the first 50 USI numbers.
$i = 0;
if ($handle) {
	while (!feof($handle)) {
		$buffer = fgets($handle, 1024);
		$row = explode("|",$buffer);
		echo "USI#: ".$row[4]."<br />\n";
		if ($i == 50) break; else $i++;
	}
	fclose($handle);
}
?>