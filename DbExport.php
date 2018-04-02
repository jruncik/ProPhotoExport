<?php

require 'FileInfo.php';
require 'OrderInfo.php';

class DbExport
{
	public function __construct()
	{
		$this->galeries = new Galeries();
		$this->InitialzeFromDbNew();
	}

	// Vycist informaceogaleriich
	// user a knemu pakobjednavku
	
	private function InitialzeFromDbNew()
	{
		$attachedFiles = $this->ReadAtachedFilesInfo();
		$orders = $this->ReadOrdersInfo();

		foreach($orders as $order)
		{
			$galId 		= $order->GetGalleryId();
			$galeryName	= $this->GetGaleryNameFromDb($galId);
			
			$galery = $this->galeries->AddOrGetGalery($galId, $galeryName);
			$galery->AddOrGetOrder($order, $attachedFiles);
			$galId = $galId + 1;
		}
	}

	private function ReadAtachedFilesInfo()
	{
		global $wpdb;

		$attachedFiles = array();
		
		$attachedFilesDb = $wpdb->get_results("SELECT * FROM lugo_postmeta WHERE meta_key = '_wp_attached_file';", OBJECT);
		foreach($attachedFilesDb as $attachedFileDb)
		{
			$postId = (int)$attachedFileDb->post_id;
			$attachedFiles[$postId] = new FileInfo($postId, $attachedFileDb->meta_value);
			$attachedFiles[$postId]->ToString();
		}
		
		$attachedFileNamesDb = $wpdb->get_results("SELECT * FROM lugo_postmeta WHERE meta_key = 'sunshine_file_name';", OBJECT);
		foreach($attachedFileNamesDb as $attachedFilesNameDb)
		{
			$postId = (int)$attachedFilesNameDb->post_id;
			$attachedFiles[$postId]->SetFileName($attachedFilesNameDb->meta_value);
			$attachedFiles[$postId]->ToString();
		}
		
		return $attachedFiles;
	}

	private function ReadOrdersInfo()
	{
		global $wpdb;

		$orders = array();
		
		$ordersDataDb = $wpdb->get_results("SELECT * FROM lugo_postmeta WHERE meta_key = '_sunshine_order_data';", OBJECT);
		foreach($ordersDataDb as $orderDataDb)
		{
			$postId = (int)$orderDataDb->post_id;
			$orderMeta = get_post_meta($postId, '_sunshine_order_data', true);
			$orders[$postId] = new OrderInfo($orderMeta);
		}
		
		$orderItemsDb = $wpdb->get_results("SELECT * FROM lugo_postmeta WHERE meta_key = '_sunshine_order_items';", OBJECT);
		foreach($orderItemsDb as $orderItemDb)
		{
			$postId = (int)$orderItemDb->post_id;
			$orderItemMeta = get_post_meta($postId, '_sunshine_order_items', false);
			$orders[$postId]->AddOrder($orderItemMeta);
		}
		
		return $orders;
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

		$query  = 'SELECT post_title FROM lugo_posts WHERE ID = ' . $galeryId;
		$galeryName = $wpdb->get_results($query, OBJECT);
		
		print_r($galeryId);
		return $galeryName[0]->post_title;
	}

	private $galeries;
}
?>