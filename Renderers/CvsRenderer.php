<?php

class CvsRenderer implements IExportVisitor
{
	public function __construct()
	{
		$this->cvsResult = chr(0xEF) . chr(0xBB) . chr(0xBF);
	}

	public function IsGalleryVisible($galleryId)
	{
		return true;
	}

	public function VisitGaleryBegin()
	{
	}

	public function VisitGalery($galery)
	{
		$this->cvsResult .= $galery->GetName();
		$this->cvsResult .= ';;;';
		$this->cvsResult .= $galery->GetTotalPrice();
		$this->cvsResult .= "\n";
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
		$this->cvsResult .= $order->GetName();
		$this->cvsResult .= ';';
		$this->cvsResult .= $order->GetEmail();
		$this->cvsResult .= ';;';
		$this->cvsResult .= $order->GetTotalPrice();
		$this->cvsResult .= "\n";
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
		$this->cvsResult .= ';';
		$this->cvsResult .= $photoDescription->GetCategory();
		$this->cvsResult .= "\n";
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
		$this->cvsResult .= ';';
		$this->cvsResult .= $photo->GetName();
		$this->cvsResult .= ';';
		$this->cvsResult .= $photo->GetQuantity();
		$this->cvsResult .= "\n";
	}

	public function VisitPhotoEnd()
	{
	}

	public function GetCvsResult()
	{
		return $this->cvsResult;
	}

	private $cvsResult;
}
?>