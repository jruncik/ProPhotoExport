<?php
require_once('../../../../wp-load.php'); 
require_once '../Model.php';
require_once '../DbExport.php';

require_once '../Renderers/XmlRenderer.php';

$model = new DbExport();
$visitor = new XmlRenderer();
	
$galeries = $model->GetGaleries();
$galeries->Accept($visitor);

$xml_output = '<galeries>';
$xml_output .= $visitor->GetXmlResult();
$xml_output .= '</galeries>';

header("Content-type: application/xml");
header("Content-disposition: galeries.xml");
header( "Content-disposition: filename=galeries.xml");

print $xml_output;

exit;
?> 