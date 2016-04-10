<?php
/*
Plugin Name: ProPhoto Orders Export
Plugin URI: https://github.com/jruncik/ProPhotoExport
Version: 0.1
Author: Jaroslav Runcik
Description: Better view over ProPhoto orders.
*/

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
	
	public function GaleryId()
	{
		return $this->galeryId;
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

add_action('admin_menu', 'my_menu');

function my_menu()
{
    add_menu_page('ProPhoto Sorted Orders', 'ProPhoto Orders', 'export', 'my-page-slug', 'sr_sort_orders');
}

function sr_sort_orders()
{
   	global $wpdb;
 
 	$mediaDb = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_wp_attached_file';", OBJECT );
 	$ordersDb = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'pfp_order_%'", OBJECT );

 	$media = array();
	
 	foreach($mediaDb as $mediumDb)
 	{
 		$media[(int)$mediumDb->post_id] = $mediumDb->meta_value;
    }

	$galeries = new Galeries();
	
 	foreach($ordersDb as $orderDb)
 	{
 		$order = json_decode($orderDb->option_value);
		
		if($order->{'status'} == 'open')
		{
			$galery = $galeries->AddOrGetGalery($order->galleryID);
			$galery->AddOrder($order, $media);
	
			sr_print_order($order, $media);
		}
    }
	
	$galery = $galeries->GetGalery('2089');
	echo $galery->GetTotalPrice();
}

function sr_print_order($order, $media)
{
	print '<table style="width:60%; border: 1px solid black">';

	sr_print_order_line('galleryID',		$order->{'galleryID'});
	sr_print_order_line('name',				$order->name);
	sr_print_order_line('email',			$order->{'email'});
	sr_print_order_line('status',			$order->{'status'});
	sr_print_order_line('paymentStatus',	$order->{'paymentStatus'});

	sr_print_photos($order->{'cart'}, $media);

	print '</table>';
	print '<br/>';
}

function sr_print_order_line($label, $value)
{
	print '<tr>';
   	print "<td style=\"width:110px\"><b>$label</b></td><td>$value</td>";
	print '</tr>';
}

function sr_print_photos($photos, $media)
{
	$totalPrice = 0;
	foreach ($photos as $photo)
 	{
		$totalPrice += sr_print_photo($photo, $media);
    }

	sr_print_blank_line();
    sr_print_order_line('totoal price:', $totalPrice);
}

function sr_print_photo($photo, $media)
{
	sr_print_blank_line();

	sr_print_order_line('imgID',		$photo->{'imgID'});
	sr_print_order_line('imgName',		$media[(int)($photo->{'imgID'})]);
	sr_print_order_line('quantity',		$photo->{'quantity'});
	sr_print_order_line('price',		$photo->{'price'});
	sr_print_order_line('productName',	$photo->{'productName'});

	return $photo->{'price'} * $photo->{'quantity'};
}

function sr_print_blank_line()
{
	print '<tr style="border: 1px solid red;height:10px">';
	//print '<td>--------------------------------------------</td>';
	print '</tr>';
}

?>