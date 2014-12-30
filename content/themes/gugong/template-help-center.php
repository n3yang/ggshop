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
					<dt>新手指南</dt>
					<dd><a href="">购买流程</a></dd>
					<dd><a href="">找回密码</a></dd>
					<dd><a href="">登陆/注册</a></dd>
					<dt>付款方式</dt>
					<dd><a href="">支付方式</a></dd>
					<dd><a href="">发票说明</a></dd>
					<dt>售后服务</dt>
					<dd><a href="returns-and-replacements">退换货规则</a></dd>
					<dt>优惠活动</dt>
					<dd><a href="how-to-use-coupons">代金券</a></dd>
					<dt><a href="contact-us">联系我们</a></dt>
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