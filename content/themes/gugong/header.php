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
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.blockUI.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$.blockUI.defaults.overlayCSS = {
		background: "#fff",
		opacity: 0.6,
		cursor: 'wait'
	};
	$.blockUI.defaults.css = {
		padding:        '20px', 
		margin:         0, 
		width:          '30%', 
		top:            '40%', 
		left:           '35%', 
		textAlign:      'center', 
		color:          '#555', 
		border:         '3px solid #aaa', 
		backgroundColor:'#fff', 
		cursor:         'wait' 
	};

	$(".search_btn").click(function() {
		location.href='/search/'+encodeURI($("#search-text").val());
	});
	$("#header_search").submit(function() {
		location.href='/search/'+encodeURI($("#search-text").val());
		return false;
	});
	$('.ggshop-message .close').click(function() {
		$(this).parent().slideUp();
	});
	setTimeout(function() {
		$('.ggshop-message').slideDown();
	}, 1000);
});
</script>
</head>
<body>

<?php
$current_user = wp_get_current_user();
?>
<div class="wrap">
	
	<div class="top_bar base-clear">
		<div class="top_left">
				<?php if ($current_user->ID==0){ ?>
				亲，欢迎您来到故宫商城！<a href="/account?login">登录</a> | <a href="/account?reg">注册</a>
				<?php }else{ ?>
				<?=$current_user->display_name;?>，欢迎您来到故宫商城！<a href="<?php echo wp_logout_url( get_permalink( wc_get_page_id( 'shop' ) ) )?>"?>退出</a>
				<?php } ?>
		</div>
		<div class="top_right">
			<span id="searchBox">
				<form id="header_search" action="/search/" method="get">
					<input id="search-text" class="search" type="text" name="s" />
					<input class="search_btn" type="submit" value="" />
				</form>
			</span>
		</div>
	</div>
<?php
// 对header背景图片进行特殊定义
$header_css = array('header');
$requri = $_SERVER['REQUEST_URI'];
if (strpos($requri,'/account?reg')===0) {
	$header_css[] = 'reg_header';
} else if (strpos($requri, '/account')===0) {
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
				<li><a class="nav6" href="/cart"></a></li>
			</ul>
		</div>
	</div>