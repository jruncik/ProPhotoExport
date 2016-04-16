<?php
class Renderer
{
	public function __construct()
	{
		$plugin_dir_url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'srStyle', $plugin_dir_url . '/css/sr_orders_export.css', false, '1.1', 'all');
	}
	
	public function RenderGaleryInfo($galery)
	{		
		$this->RenderGaleryBegin();
		$this->RenderGaleryHeader($galery);
		
		foreach($galery->GetOrders() as $order)
		{
			$this->RenderOrderBegin();
			$this->RenderCustomerInfo($order, 'srCustomerList', 'srTCCustomer');
			$this->RenderOrderEnd();
		}
		$this->RenderGaleryEnd();
	}

	public function RenderGaleryDetails($galery)
	{
		$this->RenderGaleryBegin();
		$this->RenderGaleryHeader($galery);
		
		foreach($galery->GetOrders() as $order)
		{
			$this->RenderOrderBegin();
			$this->RenderCustomerInfo($order, 'srCustomer', 'srTC');
			
			foreach($order->GetPhotosBySize() as $photos)
			{
				$this->RenderPhotos($photos);
			}
			$this->RenderOrderEnd();
		}
		$this->RenderGaleryEnd();
	}

	private function RenderGaleryBegin()
	{
		print '<div class="srGalery">';
	}

	private function RenderGaleryEnd()
	{
		print '</div>';
	}
	
	private function RenderGaleryHeader($galery)
	{
		print '<div class="srGaleryHeader">';
			print '<div class="srTR">';
				print '<div class="srGaleryName">';
					print $galery->GetName();
				print '</div>';

				print '<div class="srTC">';
					print $galery->GetTotalPrice();
					print ' Kc';
				print '</div>';
			print '</div>';
		print '</div>';
	}
	
	private function RenderOrderBegin()
	{
		print '<div class="srOrder">';
	}
	
	private function RenderOrderEnd()
	{
		print '</div>';
	}
	
	private function RenderCustomerInfo($order, $styleName, $cellStyleName)
	{
		print '<div class="';
		print $styleName;
		print '">';
		
			print "<div class=\"$cellStyleName\">";
				print $order->GetName();
			print '</div>';
			
			print "<div class=\"$cellStyleName\">";
				print $order->GetEmail();
			print '</div>';
			
			print "<div class=\"$cellStyleName\">";
				print $order->GetTotalPrice();
				print ' Kc';
			print '</div>';
			
		print '</div>';
	}

	private function RenderPhotos($photos)
	{
		print '<div class="srPhotosType">';
			print '<div class="srPhotoType">';
				print $photos->GetCategory();
			print '</div>';
			
			print '<div class="srPhotos">';
		
				foreach($photos->GetPhotos() as $photo)
				{
					$this->RenderPhoto($photo);
				}
				
			print '</div>';
		print '</div>';
	}
	
	private function RenderPhoto($photo)
	{
		if ($photo->GetName() == null)
		{
			return;
		}
		
		print '<div class="srPhoto">';
			print '<div class="srTC">';
				print $photo->GetName();
			print '</div>';

			print '<div class="srTC">';
				print $photo->GetQuantity();
			print 'x</div>';

		print '</div>';
	}
}
?>