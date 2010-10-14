<?
function open_dict($type) {
    $handle = @fopen("$type.dict", "r");
    if ($handle) {
        $i = 0;
        $fields = array();
        while (!feof($handle)) {
            $buffer = fgets($handle, 1024);
            $line = explode("\t",$buffer);
            $fields[$i]['name'] = array_shift($line);
            $fields[$i]['beg'] = array_shift($line);
            $fields[$i]['length'] = array_shift($line);
            $fields[$i]['description'] = array_shift($line);
            $i++;
        } //while
        fclose($handle);
        return $fields;
    } else return false;
}

function parse_line($line_string,&$dict) {
    $line = array();
    if (is_array($dict))
        foreach ($dict AS $params)
            $line[$params['name']] = substr($line_string,$params['beg']-1,$params['length']);
    return $line;
}

?>