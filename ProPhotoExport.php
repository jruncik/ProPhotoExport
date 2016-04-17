<?php
/*
Plugin Name: ProPhoto Orders Export
Plugin URI: https://github.com/jruncik/ProPhotoExport
Version: 0.1
Author: Jaroslav Runcik
Description: Better view over ProPhoto orders.
*/

require 'Model.php';
require 'DbExport.php';

require 'Renderers/SimpleRenderer.php';
require 'Renderers/HtmlRenderer.php';



add_action('admin_menu', 'my_menu');

function my_menu()	
{
    add_menu_page('ProPhoto Orders Info', 'ProPhoto Orders Info', 'export', 'sr_orders_page_slug_info', 'sr_orders_info');
	add_menu_page('ProPhoto Orders Details', 'ProPhoto Orders Details', 'export', 'sr_orders_page_slug_details', 'sr_orders_details');
}

function sr_orders_info()
{
	echo '<BR/>';
	
	$ppExport = new ProPhotoExport();
	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRenderer($plugin_dir_url);
	
	$ppExport->GetGaleries()->Accept($visitor);
}

function sr_orders_details()
{
	echo '<BR/>';
	
	$ppExport = new ProPhotoExport();
	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRenderer($plugin_dir_url);
	
	$ppExport->GetGaleries()->Accept($visitor);
}
?>