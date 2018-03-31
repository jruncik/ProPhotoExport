<?php
class OrderInfo
{
	public function __construct ($orderDataDb)
	{
		$this->first_name = $orderDataDb['first_name'];
		$this->last_name = $orderDataDb['last_name'];
		$this->address = $orderDataDb['address'];
		$this->city = $orderDataDb['city'];
		$this->zip = $orderDataDb['zip'];
	    $this->email = $orderDataDb['email'];
	}
	
	public function AddOrder($orderItem)
	{
	}

	public function GetFirstName() 	{ return $this->first_name; }
	public function GetLastName() 	{ return $this->last_name; }
	public function GetAddress() 	{ return $this->address; }
	public function GetCity() 		{ return $this->city; }
	public function GetZip() 		{ return $this->zip; }
	public function GetEmail() 		{ return $this->email; }
	public function GetPhone() 		{ return $this->phone; }
	
	private $first_name;
	private $last_name;
	private $address;
	private $city;
	private $zip;
	private $email;
	private $phone;
}
?>