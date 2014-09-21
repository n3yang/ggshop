<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section
 *
 * @package Gugong
 * @subpackage GugongShop
 */
?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php bloginfo( 'name' );?></title>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/base.css" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery-1.8.3.js"></script>

</head>
<body>

<div class="wrap">
	
	<div class="top_bar base-clear">
		<div class="top_left">亲，欢迎您来到故宫商城！</div>
		<div class="top_right">
			<span id="searchBox">
				<input class="search" type="text" />
				<input class="search_btn" type="submit" value="" />
			</span>
			<span>
				<a href="#">登录</a> | <a href="#">注册</a>
			</span>
			<span>
				<a id="shop" href="/cart">您的购物车</a>
			</span>
		</div>
	</div>

	<div class="header">
		<div class="nav">
			<ul>
				<li><a class="nav1" href="/"></a></li>
				<li><a class="nav2" href="#"></a></li>
				<li><a class="nav3" href="#"></a></li>
				<li class="c_nav"></li>
				<li><a class="nav4" href="#"></a></li>
				<li><a class="nav5" href="#"></a></li>
				<li><a class="nav6" href="#"></a></li>
			</ul>
		</div>
	</div>