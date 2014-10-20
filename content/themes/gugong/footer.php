<?php
/**
 * The template for displaying the footer.
 * 
 * @package Gugong
 * @subpackage GugongShop
 */
?>


	<div class="footer">
		<p>
			北京市公安局朝阳分居备案编号110105014669  京ICP证070359号  互联网药品信息服务资格证编号（京）-非经营性- 2011-0034   新出发京零字第大120007号<br>网络文化经营许可证  京网文（2011）0168-061号 Copyright  © 2004-2013  故宫GD.COM版权所有
		</p>
		<?php if ($_SERVER['REQUEST_URI'] == '/account?reg' || $_SERVER['REQUEST_URI']=='/account?login' || $_SERVER['REQUEST_URI']=='/account/lost-password' ){ ?>
		<img src="<?php bloginfo('template_url'); ?>/images/login_footer.jpg" alt="">
		<?php }else{ ?>
		<div class="qq_link">
			<a onclick="javascript:window.open('http://b.qq.com/webc.htm?new=0&amp;sid=2405232166&amp;q=7&amp;ref='+document.location, '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');">QQ1：2405232166</a>
			<a onclick="javascript:window.open('http://b.qq.com/webc.htm?new=0&amp;sid=3125281867&amp;q=7&amp;ref='+document.location, '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');">QQ2：3125281867</a>
		</div>
		<img src="<?php bloginfo('template_url'); ?>/images/footer.png" alt="">
		<?php } ?>
	</div>
</div>

</body>
</html>