<?php

class HtmlRenderer implements IExportVisitor
{
	public function __construct($plugin_dir_url)
	{
		$this->plugin_dir_url = $plugin_dir_url;
		wp_enqueue_style( 'srStyle', $plugin_dir_url . '/css/sr_orders_export.css', false, '1.1', 'all');
	}

	public function IsGalleryVisible($galleryId)
	{
		return true;
	}

	public function VisitGaleryBegin()
	{
		print'<div class="srGalery">';
	}
	
	public function VisitGalery($galery)
	{
		$fileName = $galery->GetName();
		$fileName = str_replace(' ', '_', $fileName);
		echo '<a href="'.$this->plugin_dir_url.'Export/ExportDataCvs.php?filename='.$fileName.'&galeryId='.$galery->GetGaleryId().'">'.$fileName.'.cvs</a>  ';
		echo '<a href="'.$this->plugin_dir_url.'Export/ExportDataPdf.php?filename='.$fileName.'&galeryId='.$galery->GetGaleryId().'">'.$fileName.'.pdf</a>';
		
		print '<div class="srGaleryInfo">';

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
		print '<div class="srPhotoType">';
		print $photoDescription->GetCategory();
		print '</div>';
		
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
		if ($photo->GetName() == null)
		{
			return;
		}

		print '<span>';
		print $photo->GetName();
		print '</span>';

		print '<span class="srPhotoQuantity">';
		print $photo->GetQuantity();
		print 'x    </span>';
	}
	
	public function VisitPhotoEnd()
	{
		print '</div>';
	}
	
	private $plugin_dir_url;
}
?>