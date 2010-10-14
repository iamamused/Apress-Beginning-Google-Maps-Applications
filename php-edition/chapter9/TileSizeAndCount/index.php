<?
header('content-type:text/plain;');

echo '
Mercator Projection (google ~ 10455 bytes per tile)
Zoom	# of tiles	size of map image	# of tiles total (WxH)	Storage required
';

for ($i=0;$i<=17;$i++) {

	$width = pow(2,$i);
	$entireMapTotal += $entireMap = pow($width,2);
	$resolution = 256 * $width;
	$storageTotal += $storage = 10455 * $entireMap;

	$storage = size_hum_read($storage);
echo "
$i	$width x $width	$resolution x $resolution	$entireMap	$storage";


}

$storageTotal = size_hum_read($storageTotal);

echo "

<tr>
	<td>total</td>
	<td></td>
	<td></td>
	<td><b>$entireMapTotal</b></td>
	<td><b>$storageTotal</b></td>
</tr>
";
echo '</table>';


unset($storageTotal,$entireMapTotal);

echo '<h2>Equidistant Cylindrical Projection ~ 5kb per tile</h2><table border="1" padding"2">';
echo "
<tr>
	<th>Zoom</th>
	<th># of tiles</th>
	<th>size of map image</th>
	<th># of tiles total (WxH)</th>
	<th>Storage required </th>
</tr>
";
for ($i=0;$i<=17;$i++) {

	$width = pow(2,$i);
	$entireMapTotal += $entireMap = $width * $width;
	$resolution = 256 * $width;
	$resolutionHeight = $resolution/2;
	$storageTotal += $storage = 5120 * $entireMap;

	$storage = size_hum_read($storage);


echo "
<tr>
	<td>$i</td>
	<td>$width x $width</td>
	<td>$resolution x $resolutionHeight px</td>
	<td>$entireMap</td>
	<td>$storage</td>
</tr>
";


}

$storageTotal = size_hum_read($storageTotal);
echo "
<tr>
	<td>total</td>
	<td></td>
	<td></td>
	<td><b>$entireMapTotal</b></td>
	<td><b>$storageTotal</b></td>
</tr>
";
echo '</table>';


function size_hum_read($size){
/*
Returns a human readable size
*/
  $i=0;
  $iec = array("Bytes", "kb", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  while (($size/1024)>1) {
   $size=$size/1024;
   $i++;
  }
  return substr($size,0,strpos($size,'.')+4).' '.$iec[$i];
}

?>