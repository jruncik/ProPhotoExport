<?php
$filename = $_GET['filename'];
$galeryId = $_GET['galeryId'];

$csv_output = 'Col11;Col2;Col3\nCol21;Col22;Col23\n'.$galeryId;

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv.csv");
header( "Content-disposition: filename=".$filename.".csv");
print $csv_output;
exit;
?> 