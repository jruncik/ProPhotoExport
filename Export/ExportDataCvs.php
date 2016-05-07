<?php
require_once('../../../../wp-load.php');
require_once '../Model.php';
require_once '../DbExport.php';

require_once '../Renderers/CvsRenderer.php';

$filename = $_GET['filename'];
$galeryId = $_GET['galeryId'];

$model = new DbExport();
$visitor = new CvsRenderer();

$galery = $model->GetGaleries()->GetGalery($galeryId);
$galery->Accept($visitor);
$csv_output = $visitor->GetCvsResult();

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv.csv");
header( "Content-disposition: filename=".$filename.".csv");
print $csv_output;
exit;
?>