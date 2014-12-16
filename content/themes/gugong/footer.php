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
			北京市公安局朝阳分居备案编号110105014669  京ICP证070359号  互联网药品信息服务资格证编号（京）-非经营性- 2011-0034   新出发京零字第大120007号<br>网络文化经营许可证  京网文（2011）0168-061号 Copyright  © 2004-2013  故宫GD.COM版权所有
		</p>
		<?php if (strpos($_SERVER['REQUEST_URI'], '/account')===0) { ?>
		<img src="<?php bloginfo('template_url'); ?>/images/login_footer.jpg" alt="">
		<?php }else{ ?>
		<div class="qq_link">
			<a onclick="javascript:window.open('http://b.qq.com/webc.htm?new=0&amp;sid=2287916807&amp;q=7&amp;ref='+document.location, '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');">QQ1：2287916807</a>
			<a onclick="javascript:window.open('http://b.qq.com/webc.htm?new=0&amp;sid=2695890168&amp;q=7&amp;ref='+document.location, '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');">QQ2：2695890168</a>
		</div>
		<div class="a_link">
			<a href="#"><em>1</em>购买流程</a>
			<a href="/returns-and-replacements"><em>2</em>退换货规则</a>
			<a href="/contact-us"><em>3</em>联系我们</a>
		</div>
		<img src="<?php bloginfo('template_url'); ?>/images/footer.png" alt="">
		<?php } ?>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>