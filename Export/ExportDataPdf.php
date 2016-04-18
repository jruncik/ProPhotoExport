<?php
require_once('../../../../wp-load.php'); 
require_once '../Model.php';
require_once '../DbExport.php';

header("Content-type: application/pdf");
header("Content-disposition: pdf_print.pdf");
header( "Content-disposition: filename=".$filename.".pdf");
print $csv_output;
exit;
?> 