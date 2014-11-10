<?php
/**
 * Template Name: UCP Page
 * The template for UCP (my gugong) page
 * 
 * @package Gugong
 * @subpackage GugongShop
 */


$module = get_query_var('ucp-mod');
$module_file =  get_template_directory().'/ucp/'.$module.'.php';
if (file_exists($module_file)){
	$current_user = wp_get_current_user();
	// if ($current_user->ID==0){
	// 	wp_redirect('/login');
	// 	exit;
	// }
	require_once $module_file;
} else {
	wp_redirect('/ucp/order');
}
