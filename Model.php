<?php
require 'IRendererVisitor.php';

class Galeries implements IElement
{
	public function __construct()
	{
		$this->galeries = array();
	}

	public function AddOrGetGalery($galleryId, $galeryName)
	{
		if (!array_key_exists($galleryId, $this->galeries))
		{
			$this->galeries[$galleryId] = new Galery($galleryId, $galeryName);
		}
		return $this->galeries[$galleryId];
	}

	public function GetGalery($galleryId)
	{
		return $this->galeries[$galleryId];
	}

	public function GetGaleries()
	{
		return  $this->galeries;
	}

	public function Accept($visitor)
	{
		foreach ($this->galeries as $galery)
		{
			$galery->Accept($visitor);
		}
	}

	public function GetJson()
	{
		return json_encode($this);
	}

	public $galeries;
}

class Galery implements  IElement
{
	public function __construct ($galleryId, $name)
	{
		$this->galleryId = $galleryId;
		$this->name = $name;
		$this->orders = array();
	}

	public function AddOrder($dbOrder, $media)
	{
		$orderId = $this->GenerateOrderId($dbOrder);
		$this->orders[$orderId] = new Order($dbOrder, $media);
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
		$orderId = $dbOrder->name .  '_'. $dbOrder->email;
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

	public function GetPhotosBySize()
	{
		return $this->photosBySize;
	}

	public function GetTotalPrice()
	{
		return $this->totalPrice;
	}

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
		$this->photos[] = new Photo($this, $photo, $media);
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
		$this->parent_photos = $parent_photos;
		$this->quantity = $photo->quantity;	
		$this->price = $photo->price;
		
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

	public function GetPrice()
	{
		return $this->price;
	}

	public function Accept($visitor)
	{
		$visitor->VisitPhotoBegin();
		$visitor->VisitPhoto($this);
		$visitor->VisitPhotoEnd();
	}

	private $parent_photos;
	private $name;
	private $quantity;
	private $price;
}
?>