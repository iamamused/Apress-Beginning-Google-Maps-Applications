<?php
// This will take a considerable amount of time. 1-3 minutes PER county.
set_time_limit(0);

require_once("tiger_helpers.php");

// Connect to the database
require($_SERVER['DOCUMENT_ROOT'] . '/db_credentials.php');
$conn = mysql_connect("localhost", $db_name, $db_pass);
mysql_select_db("googlemapsbook", $conn);

// Select the state and county we're interested in
$state = "06";
$county = "075";

/* [listing 11-7] */
// Open the RT1 Dictionary file
$rt1_dict = open_dict("rt1");

// Open the RT1 Data file
$handle = @fopen("./data/TGR$state$county.RT1", "r");
$tlids = array();
if ($handle) {
    while (!feof($handle)) {
        // Grab a line from the text file and parse it into an associative array.
        $buffer = fgets($handle, 4096);
        $line = parse_line($buffer,$rt1_dict);

        // Trim up the information, while making global variables
        while(list($key, $value) = each($line)) { ${$key} = trim($value); }

        // We're not interested in the line in the following cases:
        // 1. It's not CFCC type is not part of group A
        if (substr($CFCC,0,1) !== 'A') continue;

        // 2. There are no addresses for either side of the street
        if ($FRADDL == '' && $FRADDR == '') continue;

        // 3. If there is no city is associated with the road, it'll be hard to identify.
        if ($PLACEL == '' && $PLACER == '') continue;

        // The latitude and longitudes are all to 6 decimal places (note this)
        $FRLAT = substr($FRLAT,0,strlen($FRLAT)-6).'.'. substr($FRLAT,strlen($FRLAT)-6,6);
        $FRLONG = substr($FRLONG,0,strlen($FRLONG)-6).'.'. substr($FRLONG,strlen($FRLONG)-6,6);
        $TOLAT = substr($TOLAT,0,strlen($TOLAT)-6).'.'. substr($TOLAT,strlen($TOLAT)-6,6);
        $TOLONG = substr($TOLONG,0,strlen($TOLONG)-6).'.'. substr($TOLONG,strlen($TOLONG)-6,6);

        // Decide if this is a boundary of a place
        $places = array();
        if ($PLACEL != $PLACER) {
            if ($PLACEL != "") $places[] = $PLACEL;
            if ($PLACER != "") $places[] = $PLACER;
        } else {
            $places[] = $PLACEL;
        }

        // Build the queries for this TIGER/Line Item (TLID)
        $queries = array();
        foreach ($places AS $place_fips)
            $queries[] = "INSERT INTO street_names 
                (TLID,place_id,CFCC,DIR_PREFIX,NAME,TYPE,DIR_SUFFIX) 
                VALUES ('$TLID','$state$county$place_fips','$CFCC',
                 '$FEDIRP','$FENAME','$FETYPE','$FEDIRS')";
            
        if ($FRADDR != '') $queries[] = "INSERT INTO address_ranges 
                 (TLID,RANGE_ID,FIRST,LAST) VALUES ('$TLID',-1,'$FRADDR','$TOADDR')";
        if ($FRADDL != '') $queries[] = "INSERT INTO address_ranges 
                 (TLID,RANGE_ID,FIRST,LAST) VALUES ('$TLID',-2,'$FRADDL','$TOADDL')";
        $queries[] = "INSERT INTO complete_chains (TLID,SEQ,LATITUDE,LONGITUDE) 
                 VALUES ('$TLID',0,'$FRLAT','$FRLONG')";
        $queries[] = "INSERT INTO complete_chains (TLID,SEQ,LATITUDE,LONGITUDE) 
                 VALUES ('$TLID',5000,'$TOLAT','$TOLONG')";

        foreach($queries AS $query)
            if (!mysql_query($query))
                echo "Query Failed: $query (".mysql_error().")\n";

        // Hold on to the TLID for processing other record types
        $tlids[] = $TLID;
    }
}

fclose($handle);
unset($rt1_dict);
/* [listing 11-7 end] */

/* [listing 11-8] */
// Open the RT2 Dictionary file
$rt2_dict = open_dict("rt2");

// Open the RT2 Data file
$handle = @fopen("./data/TGR$state$county.RT2", "r");
if ($handle) {
    while (!feof($handle)) {
        // Grab a line from the text file and parse it into an associative array.
        $buffer = fgets($handle, 4096);
        $line = parse_line($buffer,$rt2_dict);

        // Trim up the information, while making global variables
        while(list($key, $value) = each($line)) { ${$key} = trim($value); }

        // Did we import this TLID for record type 1?
        if (!in_array($TLID,$tlids)) continue;

        // Loop through the ten points, looking for one that is 0,0
        $i=1;
        $query = "INSERT INTO complete_chains (TLID,SEQ,LATITUDE,LONGITUDE) VALUES ";
        $values = array();
        while(${"LONG$i"} != 0 && ${"LAT$i"} != 0 && $i<11) {
            $LAT = ${"LAT$i"}; $LONG = ${"LONG$i"}; // convenience
            $LAT = substr($LAT,0,strlen($LAT)-6).'.'.substr($LAT,strlen($LAT)-6,6);
            $LONG = substr($LONG,0,strlen($LONG)-6).'.'. substr($LONG,strlen($LONG)-6,6);
            $SEQ = $RTSQ.str_pad($i,2,"0",STR_PAD_LEFT);
            $values[] = "('$TLID','$SEQ','$LAT','$LONG')";
            $i++;
        }
        
        // Could execute the query string here instead. Would be MUCH faster.
        $query = $query.implode(", ",$values).";";
        if (!mysql_query($query))
            echo "Query Failed: $query (".mysql_error().")\n";
    }
}
fclose($handle);
unset($rt2_dict);
/* [listing 11-8 end] */

/* [listing 11-9] */
// Open the RTC Dictionary file
$rtc_dict = open_dict("rtc");

// Open the RTC Data file
$handle = @fopen("./data/TGR$state$county.RTC", "r");
$place_ids = array();
if ($handle) {
    while (!feof($handle)) {
        // Grab a line from the text file and parse it into an associative array.
        $buffer = fgets($handle, 4096);
        $line = parse_line($buffer,$rtc_dict);

        // Trim up the information, while making global variables
        while(list($key, $value) = each($line)) { ${$key} = trim($value); }
        $place_id = "$state$county$FIPS";

        // If the FIPS 55 Code is blank or the FIPS Type
        if ($FIPS == "") continue;
        if ($FIPSTYPE != "C") continue;
        if (in_array($place_id,$place_ids)) continue;
        $place_ids[] = $place_id;

        // All looks good. Insert into places
        $query = "INSERT INTO places (place_id,state_fips,county_fips, 
             place_fips,state_name,county_name,place_name) VALUES 
             ('$place_id','$state','$county','$FIPS','California','San Francisco','$NAME')";
        if (!mysql_query($query))
            echo "Query Failed: $query (".mysql_error().")\n";
    }
}
fclose($handle);
unset($rtc_dict);
/* [listing 11-9 end] */

?>
