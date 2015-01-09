<?php
/**
 * The template for displaying the footer.
 * 
 * @package Gugong
 * @subpackage GugongShop
 */

if ( current_user_can( 'administrator' ) ) {
    // global $wpdb;
    // echo "<pre>";
    // print_r( $wpdb->queries );
    // echo "</pre>";
}
?>


	<div class="footer">
		<p>
			京ICP证070359号<br>
			版权所有 © 2015 故宫商城
		</p>
		<?php if (strpos($_SERVER['REQUEST_URI'], '/account')===0) { ?>
		<img src="<?php bloginfo('template_url'); ?>/images/login_footer.jpg" alt="">
		<?php }else{ ?>
		<div class="qq_link">
			<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2287916807&site=qq&menu=yes">QQ1：2287916807</a>
			<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2695890168&site=qq&menu=yes">QQ2：2695890168</a>
		</div>
		<div class="a_link">
			<a href="/how-to-buy"><em>1</em>购买流程</a>
			<a href="/returns-and-replacements"><em>2</em>退换货规则</a>
			<a href="/how-to-use-coupons"><em>3</em>代金券规则</a>
			<a href="/contact-us"><em>4</em>联系我们</a>
		</div>
		<img src="<?php bloginfo('template_url'); ?>/images/footer.png" alt="">
		<?php } ?>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>