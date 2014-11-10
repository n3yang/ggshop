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

<?php
$current_user = wp_get_current_user();
?>
<div class="wrap">
	
	<div class="top_bar base-clear">
		<div class="top_left"><?php echo $current_user->ID==0 ? '亲' : $current_user->display_name; ?>，欢迎您来到故宫商城！</div>
		<div class="top_right">
			<span id="searchBox">
				<input class="search" type="text" />
				<input class="search_btn" type="submit" value="" />
			</span>
			<span>
				<?php if ($current_user->ID==0){ ?>
				<a href="/account?login">登录</a> | <a href="/account?reg">注册</a>
				<?php }else{ ?>
				<a href="<?php echo wp_logout_url( get_permalink( wc_get_page_id( 'shop' ) ) )?>"?>退出</a>
				<?php } ?>
			</span>
			<span>
				<a id="shop" href="/cart">您的购物车</a>
			</span>
		</div>
	</div>
<?php
// 对header背景图片进行特殊定义
$header_css = array('header');
$requri = $_SERVER['REQUEST_URI'];
if ($requri == '/account?reg') {
	$header_css[] = 'reg_header';
}
if ($requri == '/account?login' || $requri=='/account/lost-password') {
	$header_css[] = 'login_header';
}
$header_css = implode(' ', $header_css);
?>
	<div class="<?php echo $header_css ?>">
		<div class="nav">
			<ul>
				<li><a class="nav1" href="/shop"></a></li>
				<li><a class="nav2" href="/ucp"></a></li>
				<li><a class="nav3" href="#"></a></li>
				<li class="c_nav"></li>
				<li><a class="nav4" href="#"></a></li>
				<li><a class="nav5" href="#"></a></li>
				<li><a class="nav6" href="#"></a></li>
			</ul>
		</div>
	</div>