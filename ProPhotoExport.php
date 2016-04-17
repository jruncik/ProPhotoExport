<?php
/*
Plugin Name: ProPhoto Orders Export
Plugin URI: https://github.com/jruncik/ProPhotoExport
Version: 0.1
Author: Jaroslav Runcik
Description: Better view over ProPhoto orders.
*/

require_once 'Model.php';
require_once 'DbExport.php';

require_once 'Renderers/HtmlRenderer.php';
require_once 'Renderers/HtmlRendererList.php';
require_once 'Renderers/CvsRenderer.php';

add_action('admin_menu', 'my_menu');

function my_menu()	
{
    add_menu_page('ProPhoto Orders Info', 'ProPhoto Orders List', 'export', 'sr_orders_page_slug_list', 'sr_orders_list');
	add_menu_page('ProPhoto Orders Details', 'ProPhoto Orders Details', 'export', 'sr_orders_page_slug_details', 'sr_orders_details');

	$model = new DbExport();
	$_SESSION["model"] = $model;
}

function sr_orders_list()
{
	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRendererList($plugin_dir_url);
	
	$_SESSION["model"]->GetGaleries()->Accept($visitor);
}

function sr_orders_details()
{
	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRenderer($plugin_dir_url);
	
	$_SESSION["model"]->GetGaleries()->Accept($visitor);
}
?>