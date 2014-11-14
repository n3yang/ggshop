<?php
/**
 * The index template file for gugongshop.
 *
 * @package Gugong
 * @subpackage GugongShop
 */
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>故宫商城</title>

<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/base.css" />
<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/style.css" />
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/jquery-1.8.3.js"></script>

<style>
	html{
		height: 100%;
	}
	body{
		background: url(<?php bloginfo('template_url') ?>/images/index.jpg) center top no-repeat;
		height: 100%;
		background-size:100% 100%; 
	}
	div{
		position: absolute;
		width: 92px;
		height: 25px;
		top: 50%;
		left: 50%;
		margin-left: -46px;
		margin-top: -13px;
	}
</style>
</head>
<body>
	<div>
		<a href="#"><img src="<?=bloginfo('template_url')?>/images/index_btn.png" alt="" onmouseover="javascript:this.src='<?=bloginfo('template_url')?>/images/index_btn_over.png'" onmouseout="javascript:this.src='<?=bloginfo('template_url')?>/images/index_btn.png'"></a>
	</div>
</body>
</html>