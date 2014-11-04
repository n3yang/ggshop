<?php
/**
 * The template for displaying all pages
 *
 * @package Gugong
 * @subpackage GugongShop
 */

get_header(); ?>

	<div class="main server_404">
	
		<div class="wrap_404">
			<div class="link_404">
				<a href="/">返回首页</a>
				<a href="javascript:history.back(1);">返回上一页</a>
			</div>
			<img src="<?php bloginfo('template_url') ?>/images/404.png" alt="">
		</div>
		
	</div>
<?php
get_footer(); ?>