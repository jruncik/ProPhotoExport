<?php
/*
Plugin Name: ProPhoto Orders Export
Plugin URI: https://github.com/jruncik/ProPhotoExport
Version: 0.1
Author: Jaroslav Runcik
Description: Better view over ProPhoto orders.
*/

class Galeries
{
	public function __construct()
	{
		$this->galeries = array();
	}
	
	public function AddOrGetGalery($galeryId)
	{
		if (!array_key_exists($galeryId, $this->galeries))
		{
			$this->galeries[$galeryId] = new Galery($galeryId);
		}
		return $this->galeries[$galeryId];
	}
	
	public function GetGalery($galeryId)
	{
		return $this->galeries[$galeryId];
	}
	
	public function GetGaleries()
	{
		return  $this->galeries;
	}
	
	private $galeries;
}

class Galery
{
	public function __construct ($galeryId)
	{
		$this->galeryId = $galeryId;
		$this->orders = array();
	}
	
	public function AddOrder($dbOrder, $media)
	{
		$orderId = $this->GenerateOrderId($dbOrder);
		$this->orders[$orderId] = new Order($dbOrder, $media);
	}
	
	public function GetGaleryId()
	{
		return $this->galeryId;
	}
	
	public function GetOrders()
	{
		return $this->orders;
	}
	
	public function GetTotalPrice()
	{
		$totalPrice = 0;
		foreach ($this->orders as $order)
		{
			$totalPrice += $order->GetTotalPrice();
		}
		return $totalPrice;
	}
	
	private function GenerateOrderId($dbOrder)
	{
		$orderId = $dbOrder->name .  '_'. $dbOrder->email;
		return str_replace(' ', '', $orderId);
	}
	
	private $galeryId;
	private $orders;	
}

class Order
{
	public function __construct($dbOrder, $media)
	{
		$this->name = $dbOrder->name;
		$this->email = $dbOrder->email;
		$this->status = $dbOrder->status;
		$this->paymentStatus = $dbOrder->paymentStatus;
		
		$this->photosBySize = array();
		
		$this->FillPhotosBySize($dbOrder, $media);
	}

	public function GetName()
	{
		return $this->name;
	}
	
	public function GetEmail()
	{
		return $this->email;
	}
	
	public function GetStatus()
	{
		return $this->status;
	}
	
	public function GetPaymentStatus()
	{
		return $this->paymentStatus;
	}
	
	public function GetTotalPrice()
	{
		return $this->totalPrice;
	}
	
	private function FillPhotosBySize($dbOrder, $media)
	{
		$this->totalPrice = 0;

		foreach ($dbOrder->cart as $photo)
		{
			$this->totalPrice += $photo->price * $photo->quantity;
			
			if (!array_key_exists($photo->productName, $this->photosBySize))
			{
				$this->photosBySize[$photo->productName] = new Photos($this, $photo->productName);
			}
			
			$this->photosBySize[$photo->productName]->AddPhoto($photo, $media);
		}
	}
	
	private $name;
	private $email;
	private $status;
	private $paymentStatus;
	private $photosBySize;
	private $totalPrice;
}

class Photos
{
	public function __construct($parent_order, $category)
	{
		$this->parent_order = $parent_order;
		$this->category = $category;
		$this->photos = array();
	}
	
	public function AddPhoto($photo, $media)
	{
		$this->photos[] = new Photo($this, $photo, $media);
	}
	
	private $parent_order;
	private $photos;
	private $category;
}

class Photo
{
	public function __construct($parent_photos, $photo, $media)
	{
		$this->parent_photos = $parent_photos;
		$this->quantity = $photo->quantity;	
		
		$fullName = $media[(int)($photo->imgID)];
		$splitedNames = explode('/', $fullName);
		$this->name = $splitedNames[count($splitedNames) - 1];
	}
	
	public function GetName()
	{
		return $this->name;
	}
	
	public function GetQuantity()
	{
		return $this->quantity;
	}
	
	public function GetTotalPrice()
	{
		return $this->totalPrice;
	}
	
	private $parent_photos;
	private $name;
	private $quantity;
}

class ProPhotoExport
{
	public function __construct()
	{
		$this->galeries = new Galeries();
		$this->InitialzeFromDb();
	}
	
	private function InitialzeFromDb()
	{
		global $wpdb;
	 
		$mediaDb = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_wp_attached_file';", OBJECT );
		$ordersDb = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'pfp_order_%'", OBJECT );

		$media = array();		
		foreach($mediaDb as $mediumDb)
		{
			$media[(int)$mediumDb->post_id] = $mediumDb->meta_value;
		}

		foreach($ordersDb as $orderDb)
		{
			$order = json_decode($orderDb->option_value);
			if($order->{'status'} == 'open')
			{
				$galery = $this->galeries->AddOrGetGalery($order->galleryID);
				$galery->AddOrder($order, $media);
			}
		}
	}

	public function GetGalery($galeryId)
	{
		return $this->galeries->GetGalery($galeryId);
	}

	public function GetGaleries()
	{
		return $this->galeries->GetGaleries($galeryId);
	}

	private $galeries;
}

class Renderer
{
	function BlankLine()
	{
		print '<tr style="border: 1px solid red;height:10px"></tr>';
	}

	public function RenderGaleryInfo($galery)
	{
		$this->RenderBegin();
		$this->RenderGalery($galery);
		$this->BlankLine();
		
		foreach($galery->GetOrders() as $order)
		{
			$this->RenderOrderInfo($order);
		}
		
		$this->RenderEnd();
	}
	
	public function RenderGalery($galery)
	{
		print '<tr>';
		
		print '<td style=\"width:110px\"><h2>GaleryId: ';
		print $galery->GetGaleryId();
		print '</td></h2>';

		print '<td><b>Total Price:</b> ';
		print $galery->GetTotalPrice();
		print '</td>';
		
		print '</tr>';
	}
	
	public function RenderOrderInfo($order)
	{
		print '<tr>';
		
		print '<td>Nmae: ';
		print $order->GetName();
		print '</td>';

		print '<td>Email: ';
		print $order->GetEmail();
		print '</td>';

		print '<td>Price: ';
		print $order->GetTotalPrice();
		print '</td>';
		
		print '</tr>';
	}
	
	private function PrintLine($label, $value)
	{
		print '<tr>';
		print "<td style=\"width:110px\"><b>$label</b></td><td>$value</td>";
		print '</tr>';
	}
	
	public function RenderBegin()
	{
		print '<table style="width:70%; border: 1px solid black">';
	}
	
	public function RenderEnd()
	{
		print '</table>';
	}
}

add_action('admin_menu', 'my_menu');

function my_menu()
{
    add_menu_page('ProPhoto Orders Info', 'ProPhoto Orders Info', 'export', 'sr_orders_page_slug_info', 'sr_orders_info');
	add_menu_page('ProPhoto Orders Details', 'ProPhoto Orders Details', 'export', 'sr_orders_page_slug_details', 'sr_orders_details');
}

function sr_orders_info()
{
	echo '<BR/>';
	echo '<BR/>';
	
	$ppExport = new ProPhotoExport();
	$renderer = new Renderer();
	
	foreach ($ppExport->GetGaleries() as $galery)
	{
		$renderer->RenderGaleryInfo($galery);
		echo '<BR/>';
	}
}

function sr_orders_details()
{
	echo '<BR/>';
	echo '<BR/>';
	
	$ppExport = new ProPhotoExport();
	$renderer = new Renderer();
	
	$galery = $ppExport->GetGalery('2089');
	
	print '<table style="width:60%; border: 1px solid black">';
	$renderer->RenderGaleryInfo($galery);
	print '</table>';
}
?>