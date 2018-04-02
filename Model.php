<?php
require 'IRendererVisitor.php';

class Galeries implements IElement
{
	public function __construct()
	{
		$this->galleries = array();
	}

	public function AddOrGetGalery($galleryId, $galeryName)
	{
		if (!array_key_exists($galleryId, $this->galleries))
		{
			$this->galleries[$galleryId] = new Galery($galleryId, $galeryName);
		}
		return $this->galleries[$galleryId];
	}

	public function GetGalery($galleryId)
	{
		return $this->galleries[$galleryId];
	}

	public function GetGaleries()
	{
		return  $this->galleries;
	}

	public function Accept($visitor)
	{
		foreach ($this->galleries as $galery)
		{
			$galery->Accept($visitor);
		}
	}

	public function GetJson()
	{
		return json_encode($this);
	}

	public function GetTotalPrice()
	{
		$totalPrice = 0;
		foreach ($this->galleries as $gallery)
		{
			$totalPrice += $gallery->GetTotalPrice();
		}
		return $totalPrice;
	}

	public $galleries;
}

class Galery implements  IElement
{
	public function __construct ($galleryId, $name)
	{
		$this->galleryId = $galleryId;
		$this->name = $name;
		$this->orders = array();
	}

	public function AddOrGetOrder($dbOrder, $media)
	{
		$orderId = $this->GenerateOrderId($dbOrder);

		if (!array_key_exists($orderId, $this->orders))
		{
			$this->orders[$orderId] = new Order($dbOrder, $media);
		}
		else
		{
			$this->orders[$orderId]->AddOrder($dbOrder, $media);
		}

		return $this->orders[$orderId];
	}

	public function GetGaleryId()
	{
		return $this->galleryId;
	}

	public function GetName()
	{
		if ($this->name != null)
		{
			return $this->name;
		}

		return 'Unknown Gallery';
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

	public function Accept($visitor)
	{
		$isVisible = $visitor->IsGalleryVisible($this->galleryId);

		if (!$isVisible) {
			return;
		}

		$visitor->VisitGaleryBegin($this);
		$visitor->VisitGalery($this);

		foreach ($this->orders as $order)
		{
			$order->Accept($visitor);
		}

		$visitor->VisitGaleryEnd($this);
	}

	private function GenerateOrderId($dbOrder)
	{
		$orderId = $this->galleryId . $dbOrder->GetName() .  '_'. $dbOrder->GetEmail();
		return str_replace(' ', '', $orderId);
	}

	private $galleryId;
	private $orders;
	private $name;
	private $isVisible;
}

class Order implements  IElement
{
	public function __construct($dbOrder, $media)
	{
		$this->photosBySize	= array();
		$this->totalPrice 	= 0;

		$this->name 			= $dbOrder->GetName();
		$this->email 			= $dbOrder->GetEmail() . ' - ' . $dbOrder->GetPhone();
		$this->status 			= $dbOrder->status;
		$this->paymentStatus 	= $dbOrder->paymentStatus;
		

		$this->AddOrder($dbOrder, $media);
	}

	public function AddOrder($dbOrder, $media)
	{
		foreach ($dbOrder->GetOrderItems() as $orderItem)
		{
			$this->FillPhotosBySize($orderItem, $media);
		}	
	}

	public function GetName()			{ return $this->name; }
	public function GetEmail()			{ return $this->email; }
	public function GetStatus()			{ return $this->status; }
	public function GetPaymentStatus()	{ return $this->paymentStatus; }
	public function GetPhotosBySize()	{ return $this->photosBySize; }
	public function GetTotalPrice()		{ return $this->totalPrice; }

	public function Accept($visitor)
	{
		$visitor->VisitCustomerBegin();
		$visitor->VisitCustomer($this);

		foreach ($this->photosBySize as $photos)
		{
			$photos->Accept($visitor);
		}

		$visitor->VisitCustomerEnd();
	}

	private function FillPhotosBySize($orderItem, $media)
	{
		$this->totalPrice += $orderItem->GetPrice() * $orderItem->GetQty();		
		$photoSize = $orderItem->GetProductName();
		if (!array_key_exists($photoSize, $this->photosBySize))
		{
			$this->photosBySize[$photoSize] = new Photos($this, $photoSize);
		}

		$this->photosBySize[$photoSize]->AddPhoto($orderItem, $media);
	}

	private $name;
	private $email;
	private $status;
	private $paymentStatus;
	private $photosBySize;
	private $totalPrice;
}

class Photos implements  IElement
{
	public function __construct($parent_order, $category)
	{
		$this->parent_order = $parent_order;
		$this->category = $category;
		$this->photos = array();
	}

	public function AddPhoto($photo, $media)
	{
		$photoId = $photo->GetImageId();

		if (!array_key_exists($photoId, $this->photos))
		{
			$this->photos[$photoId] = new Photo($this, $photo, $media);
		}
		else
		{
			$this->photos[$photoId]->AddQuantity($photo->quantity);
		}
	}

	public function GetPhotos()
	{
		return $this->photos;
	}

	public function GetCategory()
	{
		return $this->category;
	}

	public function Accept($visitor)
	{
		$visitor->VisitPhotoDescriptionBegin();
		$visitor->VisitPhotoDescription($this);

		foreach ($this->photos as $photo)
		{
			$photo->Accept($visitor);
		}

		$visitor->VisitPhotoDescriptionEnd();
	}

	private $parent_order;
	private $photos;
	private $category;
}

class Photo implements  IElement
{
	public function __construct($parent_photos, $photo, $media)
	{
		$this->parent_photos 	= $parent_photos;
		$this->quantity 		= $photo->GetQty();
		$this->price 			= $photo->GetPrice();
		$this->quantityAdded 	= false;
		$this->name 			= $photo->GetImageName();
	}

	public function AddQuantity($addQuantity)
	{
		$this->quantity += $addQuantity;
		$this->quantityAdded = true;
	}

	public function IsQuantityAdded()
	{
		return $this->quantityAdded;
	}

	public function GetName()
	{
		return $this->name;
	}

	public function GetQuantity()
	{
		return $this->quantity;
	}

	public function GetPrice()
	{
		return $this->price;
	}

	public function Accept($visitor)
	{
		$visitor->VisitPhotoBegin($this);
		$visitor->VisitPhoto($this);
		$visitor->VisitPhotoEnd();
	}

	private $parent_photos;
	private $name;
	private $quantity;
	private $price;
	private $quantityAdded;
}
?>