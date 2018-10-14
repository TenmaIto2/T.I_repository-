<?php
$filename = 'mission_1-2_ito.txt';
$fp = fopen($filename, 'w');
fwrite($fp, 'Hello World');
fclose($fp);
?>