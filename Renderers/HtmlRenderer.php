<?php

class HtmlRenderer implements IExportVisitor
{
	public function VisitGaleryBegin()
	{
		print'<div class="srGalery">';
	}
	
	public function VisitGalery($galery)
	{
		print '<div>';

		print '<span class="srGaleryName">';
		print $galery->GetName();
		print '</span>';

		print '<span class="srGaleryPrice">';
		print $galery->GetTotalPrice();
		print ' Kč</span>';

		print '</div>';
	}

	public function VisitGaleryEnd()
	{
		print '</div>';
	}

	////////////////////////////////////////////////////////////////////
	public function VisitCustomerBegin()
	{
		print '<div class="srCustomer">';
	}
	
	public function VisitCustomer($order)
	{
		print '<span class="srCustomerName">';
		print $order->GetName();
		print '</span>';

		print '<span class="srCustomerEmail">';
		print $order->GetEmail();
		print '</span>';

		print '<span class="srCustomerPrice">';
		print $order->GetTotalPrice();
		print ' Kč</span>';
	}

	public function VisitCustomerEnd()
	{
		print '</div>';
	}
	
	////////////////////////////////////////////////////////////////////
	public function VisitPhotoDescriptionBegin()
	{
	}
	
	public function VisitPhotoDescription($photoDescription)
	{
		print '<span class="srPhotoType">';
		print $photoDescription->GetCategory();
		print '</span>';
		
		print '<div class="srPhotos">';
	}

	public function VisitPhotoDescriptionEnd()
	{
		print '</div>';
	}
	
	////////////////////////////////////////////////////////////////////
	public function VisitPhotoBegin()
	{
		print '<div class="srPhoto">';
	}
	
	public function VisitPhoto($photo)
	{
		print '<span>';
		print $photo->GetName();
		print '</span>';

		print '<span class="srPhotoQuantity">';
		print $photo->GetQuantity();
		print 'x</span>';
	}
	
	public function VisitPhotoEnd()
	{
		print '</div>';
	}
}
?>