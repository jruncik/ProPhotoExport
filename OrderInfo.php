<?php
require 'OrderItem.php';

class OrderInfo
{
	public function __construct ($orderDataDb)
	{
		$this->items = array();
		$this->galleryId = (int)0;
		
		$this->name 	= $orderDataDb['last_name']. ' ' . $orderDataDb['first_name'];
		$this->address	= $orderDataDb['address'];
		$this->city 	= $orderDataDb['city'];
		$this->zip 		= $orderDataDb['zip'];
	    $this->phone 	= $orderDataDb['phone'];
		$this->email 	= $orderDataDb['email'];
	}
	
	public function AddOrder($orderItemsDb)
	{
		foreach ($orderItemsDb as $orderItemDb)
		{
			foreach ($orderItemDb as $orderItemDataDb)
			{
				$orderItem = new OrderItem($orderItemDataDb);
				
				$this->items[] = $orderItem;
				$this->galleryId = $orderItem->GetGalleryId();
			
				if ($this->galleryId == 0)
				{
					$this->galleryId = $orderItem->GetGalleryId();
				}
				else
				{
					if ($this->galleryId != $orderItem->GetGalleryId())
					{
						throw new Exception('Multiple galeries per one orderdetected!');
					}
				}
			}
		}
	}
	
	public function GetOrderItems()	{ return $this->items; }

	public function GetName() 		{ return $this->name; }
	public function GetAddress() 	{ return $this->address; }
	public function GetCity() 		{ return $this->city; }
	public function GetZip() 		{ return $this->zip; }
	public function GetEmail() 		{ return $this->email; }
	public function GetPhone() 		{ return $this->phone; }
	public function GetGalleryId() 	{ return $this->galleryId; }
	
	// private $items;
	private $name;
	private $address;
	private $city;
	private $zip;
	private $email;
	private $phone;
	private $galleryId;
}
?>