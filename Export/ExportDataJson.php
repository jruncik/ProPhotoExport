<?php
require_once('../../../../wp-load.php'); 
require_once '../Model.php';
require_once '../DbExport.php';

$model = new DbExport();

header("Content-type: application/json");
header("Content-disposition: galeries.json");
header( "Content-disposition: filename=galeries.json");

print $model->GetGaleries()->GetJson();
exit;
?> 