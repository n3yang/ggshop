<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Pinwu
 */
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php bloginfo( 'name' );?></title>
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style/ui.css"/>

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/global.js"></script>

<?php wp_head();?>
</head>

<body>
<!-- site-nav -->
<div>
