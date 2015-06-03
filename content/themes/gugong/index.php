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
<meta property="wb:webmaster" content="92658cbfc3f9c5f5" />
<meta name="renderer" content="webkit" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
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
		<a href="/shop"><img src="<?=bloginfo('template_url')?>/images/index_btn.png" alt="" onmouseover="javascript:this.src='<?=bloginfo('template_url')?>/images/index_btn_over.png'" onmouseout="javascript:this.src='<?=bloginfo('template_url')?>/images/index_btn.png'"></a>
	</div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?ea16ca62bf991c2150676da256bd23d0";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>