<?php

class HtmlRendererList implements IExportVisitor
{
	public function __construct($plugin_dir_url)
	{
		$this->plugin_dir_url = $plugin_dir_url;
		wp_enqueue_style( 'srStyle', $plugin_dir_url . '/css/sr_orders_export.css', false, '1.1', 'all');
	}
	
	public function VisitGaleryBegin()
	{
		print'<div class="srGalery">';
	}
	
	public function VisitGalery($galery)
	{
		$fileName = $galery->GetName();
		$fileName = str_replace(' ', '_', $fileName);
		echo '<a href="'.$this->plugin_dir_url.'Export/ExportDataListCvs.php?filename='.$fileName.'&galeryId='.$galery->GetGaleryId().'">'.$fileName.'.cvs</a>  ';
		echo '<a href="'.$this->plugin_dir_url.'Export/ExportDataListPdf.php?filename='.$fileName.'&galeryId='.$galery->GetGaleryId().'">'.$fileName.'.pdf</a>';
		
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
		print '<div class="srCustomerList">';
	}
	
	public function VisitCustomer($order)
	{
		print '<span class="srCustomerListName">';
		print $order->GetName();
		print '</span>';

		print '<span class="srCustomerListPrice">';
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