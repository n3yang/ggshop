<?php
/**
 * Template Name: Help Center Page
 * The template for help center page
 * 
 * @package Gugong
 * @subpackage GugongShop
 */
get_header()
?>
	<div class="main help_center_bg">


		<div class="help_center_wrap base-clear">
	
			<div class="help_center_menu">
				<dl>
					<dt>购物指南</dt>
					<dd><a href="how-to-buy">购买流程</a></dd>
					<dd><a href="how-to-get-password-back">找回密码</a></dd>
					<dd><a href="how-to-register-or-login">登陆/注册</a></dd>
					<dd><a href="how-to-get-invoice">商品发票</a></dd>
					<dt><a href="how-to-checkout">付款</a></dt>
					<dt><a href="how-to-deliver-product">配送与收货</a></dt>
					<dt>售后服务</dt>
					<dd><a href="returns-and-replacements">退换货规则</a></dd>
					<dt>优惠活动</dt>
					<dd><a href="how-to-use-coupons">代金券</a></dd>
					<dt><a href="contact-us">联系我们</a></dt>
					<dt><a href="partner-sites">友情链接</a></dt>
				</dl>
			</div>

			<div class="user_center_box">
				
				<div class="help_center_title">
					<h3><?php the_title() ?></h3>
					<hr>
				</div>
				<div class="help_center_content">
						<?php the_post();the_content(); ?>
				</div>

			</div>

		</div>


	</div>


<?php
get_footer();
?>