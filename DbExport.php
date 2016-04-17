<?php
class DbExport
{
	public function __construct()
	{
		$this->galeries = new Galeries();
		$this->InitialzeFromDb();
	}
	
	private function InitialzeFromDb()
	{
		global $wpdb;
	 
		$mediaDb = $wpdb->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_wp_attached_file';", OBJECT);
		$ordersDb = $wpdb->get_results("SELECT * FROM wp_options WHERE option_name LIKE 'pfp_order_%'", OBJECT);

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
				$galeryName = $this->GetGaleryNameFromDb($order->galleryID);
				$galery = $this->galeries->AddOrGetGalery($order->galleryID, $galeryName);
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
		return $this->galeries;
	}
	
	private function GetGaleryNameFromDb($galeryId)
	{
		global $wpdb;
		$query  = 'SELECT post_title FROM wp_posts WHERE ID = ' . $galeryId;
		$galeryName = $wpdb->get_results($query, OBJECT);
		return $galeryName[0]->post_title;
	}

	private $galeries;
}
?>