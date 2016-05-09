<?php
define('FPDF_FONTPATH','../fpdf/font/');
require_once('../fpdf/fpdf.php');

class PdfRendererList extends FPDF implements IExportVisitor
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
		$this->fontName = 'calibri';
	}

	public function VisitGalery($galery)
	{
		$this->SetFillColor(240, 240, 240);
		$this->SetTextColor(0, 0, 0);
		$this->SetLineWidth(0.1);
		$this->SetFont($this->fontName, '', 16);

		$this->Cell(140, 7, iconv("UTF-8", "cp1250", $galery->GetName()),       'B', 0, 'L');
		$this->Cell(30, 7, iconv("UTF-8", "cp1250", $galery->GetTotalPrice()) . $this->kc, 'B', 0, 'R');
		$this->Ln();
		$this->Ln();

		$this->SetFont($this->fontName, '', 12);
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
		$this->Cell(80, 7, iconv("UTF-8", "cp1250", $order->GetName()),       	  '', 0, 'L', $this->odd);
		$this->Cell(60, 7, iconv("UTF-8", "cp1250", $order->GetEmail()),       	  '', 0, 'L', $this->odd);
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
	public function VisitPhotoBegin($photo)
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
	private $fontName;
}
?>