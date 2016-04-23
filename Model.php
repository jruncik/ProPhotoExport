<?php
require 'IRendererVisitor.php';

class Galeries implements IElement
{
	public function __construct()
	{
		$this->galeries = array();
	}
	
	public function AddOrGetGalery($galeryId, $galeryName)
	{
		if (!array_key_exists($galeryId, $this->galeries))
		{
			$this->galeries[$galeryId] = new Galery($galeryId, $galeryName);
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
	public function __construct ($galeryId, $name)
	{
		$this->galeryId = $galeryId;
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
		return $this->galeryId;
	}
	
	public function GetName()
	{
		if ($this->name != null)
		{
			return $this->name;
		}
		
		return 'UnknownGalery';
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
		$visitor->VisitGaleryBegin();
		$visitor->VisitGalery($this);

		foreach ($this->orders as $order)
		{
			$order->Accept($visitor);
		}
		
		$visitor->VisitGaleryEnd();
	}
	
	private function GenerateOrderId($dbOrder)
	{
		$orderId = $dbOrder->name .  '_'. $dbOrder->email;
		return str_replace(' ', '', $orderId);
	}
	
	private $galeryId;
	public $orders;
	public $name;	
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
	
	public $name;
	public $email;
	public $status;
	public $paymentStatus;
	public $photosBySize;
	public $totalPrice;
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
	public $photos;
	public $category;
}

class Photo implements  IElement
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
	
	public function Accept($visitor)
	{
		$visitor->VisitPhotoBegin();
		$visitor->VisitPhoto($this);
		$visitor->VisitPhotoEnd();
	}
	
	private $parent_photos;
	public $name;
	public $quantity;
}
?>