<?php

class XmlRenderer implements IExportVisitor
{
		public function IsGalleryVisible($galleryId)
	{
		return true;
	}

	public function VisitGaleryBegin()
	{
	}

	public function VisitGalery($galery)
	{
		$this->NL();
		$this->xmlResult .= '<galery name="';
		$this->xmlResult .= $galery->GetName();
		$this->xmlResult .= '" price="';
		$this->xmlResult .= $galery->GetTotalPrice();
		$this->xmlResult .= '">';
		$this->NL();
		$this->xmlResult .= '<orders>';
		$this->NL();
	}

	public function VisitGaleryEnd()
	{
		$this->xmlResult .= '</orders>';
		$this->NL();
		$this->xmlResult .= '</galery>';
		$this->NL();
	}

	////////////////////////////////////////////////////////////////////
	public function VisitCustomerBegin()
	{
	}

	public function VisitCustomer($order)
	{
		$this->xmlResult .= '<customer name="';
		$this->xmlResult .= $order->GetName();
		$this->xmlResult .= '" price="';
		$this->xmlResult .= $order->GetTotalPrice();
		$this->xmlResult .= '" email="';
		$this->xmlResult .= $order->GetEmail();
		$this->xmlResult .= '">';
		$this->NL();
		$this->xmlResult .= '<photoTypes>';
		$this->NL();
	}

	public function VisitCustomerEnd()
	{
		$this->xmlResult .= '</photoTypes>';
		$this->NL();
		$this->xmlResult .= '</customer>';
		$this->NL();
	}

	////////////////////////////////////////////////////////////////////
	public function VisitPhotoDescriptionBegin()
	{
	}

	public function VisitPhotoDescription($photoDescription)
	{
		$this->xmlResult .= '<type name="';
		$this->xmlResult .= $photoDescription->GetCategory();
		$this->xmlResult .= '">';
		$this->NL();
		$this->xmlResult .= '<photos>';
		$this->NL();
	}

	public function VisitPhotoDescriptionEnd()
	{
		$this->xmlResult .= '</photos>';
		$this->NL();
		$this->xmlResult .= '</type>';
		$this->NL();
	}

	////////////////////////////////////////////////////////////////////
	public function VisitPhotoBegin()
	{
	}

	public function VisitPhoto($photo)
	{
		$this->xmlResult .= '<photo name="';
		$this->xmlResult .= $photo->GetName();
		$this->xmlResult .= '" quantity="';
		$this->xmlResult .= $photo->GetQuantity();
		$this->xmlResult .= '" price="';
		$this->xmlResult .= $photo->GetPrice();
		$this->xmlResult .= '"/>';
		$this->NL();
	}

	public function VisitPhotoEnd()
	{
	}

	public function GetXmlResult()
	{
		return $this->xmlResult;
	}

	private function NL()
	{
		// $this->xmlResult .="\n";
	}

	private $xmlResult;
}
?>