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
require_once 'Renderers/HtmlRendererCombo.php';

add_action('admin_menu', 'my_menu');

function my_menu()
{
	add_menu_page('Orders Info', 'Orders List', 'export', 'sr_orders_page_slug_list', 'sr_orders_list');
	add_menu_page('Orders Details', 'Orders Details', 'export', 'sr_orders_page_slug_details', 'sr_orders_details');

	$model = new DbExport();
	$_SESSION["model"] = $model;
}

function sr_orders_list()
{
	enqueueAdminJs();

	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRendererList($plugin_dir_url);

	ownerDivBegin($plugin_dir_url);
	$_SESSION["model"]->GetGaleries()->Accept($visitor);
	ownerDivEnd();
}

function sr_orders_details()
{
	enqueueAdminJs();

	$plugin_dir_url = plugin_dir_url( __FILE__ );
	$visitor = new HtmlRenderer($plugin_dir_url);

	ownerDivBegin($plugin_dir_url);
	$_SESSION["model"]->GetGaleries()->Accept($visitor);
	ownerDivEnd();
}

function renderComboBox()
{
	$visitor = new HtmlRendererCombo();

	print '<br/>';
	print '<br/>';

	print '<select id="comboGalleries" onchange="change(this)">';
	print '<option value="-1">All Galleries</option>';
	$_SESSION["model"]->GetGaleries()->Accept($visitor);
	print '</select>';

	print '<br/>';
}

function enqueueAdminJs()
{
	$handle = 'ProPhotoExport.js';
	$src = plugins_url( 'ProPhotoExport.js', __FILE__ );
	$deps = array();
	$ver = '0.1';

	wp_enqueue_script($handle, $src, $deps, $ver, true );
}

function ownerDivBegin($plugin_dir_url)
{
	print '<br/>';
	echo '<a href="' . $plugin_dir_url . 'Export/ExportDataXml.php">galeries.xml</a>';
	renderComboBox();

	print "\n";
	print '<div id="ProPhotoExport">';
	print "\n";
}

function ownerDivEnd()
{
	print "\n";
	print '</div>';
	print "\n";
}

?>