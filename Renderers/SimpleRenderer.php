<?php

class SimpleRenderer implements IExportVisitor
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
		print $galery->GetName();
		print ', ';
		print $galery->GetTotalPrice();
		print '<BR/>';
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
		print $order->GetName();
		print ', ';
		print $order->GetEmail();
		print ', ';
		print $order->GetTotalPrice();
		print '<BR/>';
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
		print $photoDescription->GetCategory();
		print '<BR/>';
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
		print $photo->GetName();
		print ', ';
		print $photo->GetQuantity();
		print '<BR/>';
	}

	public function VisitPhotoEnd()
	{
	}
}
?>