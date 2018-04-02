<?php
class OrderItem
{
	public function __construct ($orderItemDataDb)
	{
		$this->image_id 	= $orderItemDataDb['image_id'];	
		$this->gallery_id 	= $orderItemDataDb['gallery_id'];	
		$this->product_id 	= $orderItemDataDb['product_id'];	
		$this->qty 			= $orderItemDataDb['qty'];
		$this->price 		= $orderItemDataDb['price'];
		$this->image_name 	= $orderItemDataDb['image_name'];
		$this->product_name	= $orderItemDataDb['product_name'];
	}

	public function GetImageId() 		{ return $this->image_id; }
	public function GetGalleryId() 		{ return $this->gallery_id; }
	public function GetProductId() 		{ return $this->product_id; }
	public function GetQty() 			{ return $this->qty; }
	public function GetPrice() 			{ return $this->price; }
	public function GetImageName() 		{ return $this->image_name; }
	public function GetProductName() 	{ return $this->product_name; }

	private $image_id;		// 	";i: 119;
	private $gallery_id;	//	";i: 80;
	private $product_id;	//	";i: 122;
	private $qty;
	private $price;
	private $image_name;
	private $product_name;
}
?>