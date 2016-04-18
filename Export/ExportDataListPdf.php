<?php
require_once('../../../../wp-load.php'); 
require_once '../Model.php';
require_once '../DbExport.php';

require_once '../Renderers/PdfRendererList.php';

$filename = $_GET['filename'];
$galeryId = $_GET['galeryId'];

$model = new DbExport();
$visitor = new PdfRendererList();
	
$galery = $model->GetGaleries()->GetGalery($galeryId);
$galery->Accept($visitor);

$visitor->Output();
?> 