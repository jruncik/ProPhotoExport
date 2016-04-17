<?php
require_once('../../../wp-load.php'); 
require('/fpdf/fpdf.php');

require_once 'Model.php';
require_once 'DbExport.php';

require_once 'Renderers/HtmlRenderer.php';
require_once 'Renderers/CvsRenderer.php';

$filename = $_GET['filename'];
$galeryId = $_GET['galeryId'];

$model = new DbExport();
$visitor = new CvsRenderer();
	
$galery = $model->GetGaleries()->GetGalery($galeryId);
$galery->Accept($visitor);
$csv_output = $visitor->GetCvsResult();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();

//header("Content-type: application/pdf");
//header("Content-disposition: pdf_print.pdf");
//header( "Content-disposition: filename=".$filename.".pdf");
//print $csv_output;
//exit;
?> 