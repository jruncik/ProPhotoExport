<?php
define('FPDF_FONTPATH','../fpdf/font/');
require_once('../fpdf/fpdf.php');

class PdfRenderer extends FPDF implements IExportVisitor
{
	public function IsGalleryVisible($galleryId)
	{
		return true;
	}

	public function VisitGaleryBegin()
	{
		$this->AddPage();
		$this->kc = iconv("UTF-8", "cp1250", " Kč");
		$this->odd  = false;

		$this->AddFont('calibri', '', 'calibri.php');
		$this->AddFont('calibriB', '', 'calibrib.php');
	}

	public function VisitGalery($galery)
	{
		$this->SetFillColor(240, 240, 240);
		$this->SetTextColor(0, 0, 0);
		$this->SetLineWidth(0.1);
		$this->SetFont('calibri', '', 16);

		$this->Cell(140, 7, iconv("UTF-8", "cp1250", $galery->GetName()),       'B', 0, 'L');
		$this->Cell(30, 7, iconv("UTF-8", "cp1250", $galery->GetTotalPrice()) . $this->kc, 'B', 0, 'R');
		$this->Ln();
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
		$this->SetFont('calibri', '', 12);
		$this->Cell(80, 7, iconv("UTF-8", "cp1250", $order->GetName()),       	  'T', 0, 'L');
		$this->Cell(60, 7, iconv("UTF-8", "cp1250", $order->GetEmail()),       	  'T', 0, 'L');
		$this->Cell(30, 7, iconv("UTF-8", "cp1250", $order->GetTotalPrice()) . $this->kc, 'T', 0, 'R');
		$this->Ln();
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
		$this->SetFont('calibriB', '', 10);
		$this->Cell(5, 7, "");
		$this->Cell(135, 7, iconv("UTF-8", "WINDOWS-1250", $photoDescription->GetCategory()));
		$this->Ln();
		$this->SetFont('calibri', '', 10);
	}

	public function VisitPhotoDescriptionEnd()
	{
	}

	////////////////////////////////////////////////////////////////////
	public function VisitPhotoBegin($photo)
	{
	}

	public function VisitPhoto($photo)
	{
		$this->Cell(15, 7, "");
		$this->Cell(105, 7, iconv("UTF-8", "WINDOWS-1250", '- ' . $photo->GetName()));
		$this->Cell(20, 7, iconv("UTF-8", "WINDOWS-1250", $photo->GetQuantity() . 'x'), '', 0, 'R');
		$this->Ln();
	}

	public function VisitPhotoEnd()
	{
	}

	private $kc;
	private $odd;
}
?>