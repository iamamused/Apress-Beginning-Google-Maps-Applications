<?php
header('Content-Type: text/xml;charset=UTF-8');
$markers = file_get_contents('data.xml');
echo <<<XML
<markers>
$markers
</markers>
XML;
?>
