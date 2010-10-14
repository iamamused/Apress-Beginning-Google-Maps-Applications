<?php
// Start our response
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><ResultSet>';

// Clean up the input
foreach ($_REQUEST AS $key=>$value) {
    $key = strtolower($key);
    if (in_array($key,array("state","city","street","number"))) {
        $value = trim($value);
        $value = preg_replace("/[^a-z0-9\s\.]/i","",$value);
        $value = ucwords($value);
        ${$key} = $value; // make it into a named global variable.
    }
}

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Try for an exact match on the city and state names
$query = "SELECT * FROM places WHERE state_name='$state' AND place_name='$city'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    // Oh well, look up the state and fuzzy match the city name
    $result = mysql_query("SELECT * FROM places WHERE state_name = '$state'");
    if (!$result || mysql_num_rows($result) == 0) die("<error></error></ResultSet>");
    $cities = array();
    for ($i=0; $i<mysql_num_rows($result); $i++) {
        $row = mysql_fetch_array($result,MYSQL_ASSOC);
        $cities['place_id'][$i] = $row['place_id'];
        $cities['accuracy'][$i] = levenshtein($row['place_name'],$city);
    }

    // Sort them by "closeness" to the requested city name and take the top one
    array_multisort($cities['accuracy'],SORT_ASC,$cities['place_id']);
    $place_id = $cities['place_id'][0];
} else {
    // We found it. Grab the place_id and continue on to phase two!
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $place_id = $row['place_id'];
}

// Search for the street name and address
$number = (int)$number;
$query = "SELECT sn.TLID, FIRST, LAST, ($number-FIRST) AS diff
    FROM street_names AS sn, address_ranges AS ar
    WHERE ar.TLID = sn.TLID
    AND sn.place_id = $place_id
    AND sn.NAME = '$street'
    AND '$number' BETWEEN ar.FIRST AND ar.LAST
    ORDER BY diff
    LIMIT 0,1";

$result = mysql_query($query);
if (mysql_num_rows($result) == 1) $row = mysql_fetch_array($result,MYSQL_ASSOC);
else die("<Error>No Matches</Error></ResultSet>");

// We should now have a single TLID, grab all of the points in the chain
$tlid = $row['TLID'];
$first_address = $row['FIRST'];
$last_address = $row['LAST'];
$query = "SELECT LATITUDE,LONGITUDE
    FROM complete_chains
    WHERE TLID='$tlid' ORDER BY SEQ";
$result = mysql_query($query);
$points = array();
for ($i=0; $i<mysql_num_rows($result); $i++) {
    $points[] = mysql_fetch_array($result,MYSQL_ASSOC);
}

// Compute the lengths of all of the segments in the chain
$segment_lengths = array();
$num_segments = count($points)-1;
for($i=0; $i<$num_segments; $i++) {
    $segment_lengths[] = line_length($points[$i],$points[$i+1]);
}
$total_length = array_sum($segment_lengths);

// Avoid divide by zero problems
if ($total_length == 0) {
    // The distances are too small to compute, return the start of the street.
    die("<Result>
        <Latitude>{$points[0]['LATITUDE']}</Latitude>
        <Longitude>{$points[0]['LONGITUDE']}</Longitude>
        </Result></ResultSet>");
}

// Compute how far along the chain our address is
$address_position = abs($number - $last_address);
$num_addresses = abs($first_address - $last_address);
$distance_along_line = $address_position/$num_addresses*$total_length;

// Figure out which segment our address is in, and where it is
$travel_distance = 0;
for($i=0; $i<$num_segments; $i++) {
    $bottom_address = $first_address + ($travel_distance / $total_length * 
 $num_addresses);
    $travel_distance += $segment_lengths[$i];
    if ($travel_distance > $distance_along_line) {
        // We've found our segment, do the final computations
        $top_address = $first_address + ($travel_distance / $total_length * 
 $num_addresses);

        // Determine how far along this segment our address is
        $seg_addr_total = abs($top_address - $bottom_address);
        $addr_position = abs($number - $bottom_address)/$seg_addr_total;
        $segment_delta = $segment_lengths[$i]*$addr_position;

        // Determine the angle of the segment
        $delta_x = abs($points[$i]['LATITUDE'] - $points[$i+1]['LATITUDE']);
        $delta_y = abs($points[$i]['LONGITUDE'] - $points[$i+1]['LONGITUDE']);
        $angle = atan($delta_y/$delta_x);

        // And you thought you'd never use trig again!
        $x = $segment_delta*cos($angle);
        $y = $segment_delta*sin($angle);
    }
}
echo("<Result>
    <Latitude>$x</Latitude>
    <Longitude>$y</Longitude>
    </Result>");


// Close our response
echo "</ResultSet>";

function line_length($point1,$point2) {
    $delta_x = abs($point1['LATITUDE'] - $point2['LATITUDE']);
    $delta_y = abs($point1['LONGITUDE'] - $point2['LONGITUDE']);
    $segment_length = sqrt($delta_x^2 + $delta_y^2);
    return $segment_length;
}

?>
