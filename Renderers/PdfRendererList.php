<?php
require_once('../fpdf/fpdf.php');

class PdfRendererList extends FPDF implements IExportVisitor
{
	public function VisitGaleryBegin()
	{
		$this->AddPage();
		$this->kc = iconv("UTF-8", "cp1250", " Kč");
		$this->odd  = false;
	}
	
	public function VisitGalery($galery)
	{
		$this->SetFillColor(240, 240, 240);
		$this->SetTextColor(0, 0, 0);
		$this->SetLineWidth(0.1);
		$this->SetFont('Arial', 'B', 18);
		
		$this->Cell(140, 7, iconv("UTF-8", "WINDOWS-1250", $galery->GetName()),       'B', 0, 'L');
		$this->Cell(30, 7, iconv("UTF-8", "cp1250", $galery->GetTotalPrice()) . $this->kc, 'B', 0, 'R');
		$this->Ln();
		$this->Ln();
		
		$this->SetFont('Arial', '', 12);
	}

	public function VisitGaleryEnd()
	{
	}

	////////////////////////////////////////////////////////////////////
	public function VisitCustomerBegin()
	{
	}
	
	public function VisitCustomer($order)
	{
		$this->Cell(140, 7, iconv("UTF-8", "WINDOWS-1250", $order->GetName()),       	  '', 0, 'L', $this->odd);
		$this->Cell(30, 7, iconv("UTF-8", "cp1250", $order->GetTotalPrice()) . $this->kc, '', 0, 'R', $this->odd);
		$this->Ln();
		
		$this->odd = !$this->odd;
	}

	public function VisitCustomerEnd()
	{
	}
	
	////////////////////////////////////////////////////////////////////
	public function VisitPhotoDescriptionBegin()
	{
	}
	
	public function VisitPhotoDescription($photoDescription)
	{
	}

	public function VisitPhotoDescriptionEnd()
	{
	}
	
	////////////////////////////////////////////////////////////////////
	public function VisitPhotoBegin()
	{
	}
	
	public function VisitPhoto($photo)
	{
	}
	
	public function VisitPhotoEnd()
	{
	}
	
	private $kc;
	private $odd;
}
?>