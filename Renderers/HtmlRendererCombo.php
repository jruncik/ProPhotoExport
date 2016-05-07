<?php
class HtmlRendererCombo implements IExportVisitor
{
	public function VisitGaleryBegin()
	{
	}
	
	public function VisitGalery($galery)
	{
		print '<option value="';
		print $galery->GetGaleryId();
		print '">';
		print $galery->GetName();
		print '</option>';
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
	
	private $plugin_dir_url;
}
?>