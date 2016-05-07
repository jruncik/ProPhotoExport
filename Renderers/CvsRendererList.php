<?php

class CvsRendererList implements IExportVisitor
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
		$this->cvsResult .= ';';
		$this->cvsResult .= $galery->GetTotalPrice();
		$this->cvsResult .= "\n";
	}

	public function VisitGaleryEnd()
	{
		$this->cvsResult .= "\n";
	}

	////////////////////////////////////////////////////////////////////
	public function VisitCustomerBegin()
	{
	}
	
	public function VisitCustomer($order)
	{
		$this->cvsResult .= $order->GetName();
		$this->cvsResult .= ';';
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
	
	public function GetCvsResult()
	{
		return $this->cvsResult;
	}
	
	private $cvsResult;
}
?>