<?php


if ($_REQUEST['ajax']){
	if ($_REQUEST['act']=='add' && !empty($_REQUEST['pid'])) {
		$new_fav = intval($_REQUEST['pid']);
		$user = wp_get_current_user();
		if ($user->ID==0) {
			exit('请登陆后再收藏');
		}
		// TODO: 检测pid的有效性
		$favorite = ggshop_get_user_favorite();
		if (array_key_exists($new_fav, $favorite)){
			exit('已添加到收藏');
		}
		$favorite[$new_fav] = array('ctime'=>time());
		update_user_meta( get_current_user_id(), 'ggshop_user_favorite',  json_encode($favorite));
		exit('已添加到收藏');
	}
	if ($_REQUEST['act']=='remove' && !empty($_REQUEST['pid'])) {
		$remove_fav = intval($_REQUEST['pid']);
		$user = wp_get_current_user();
		if ($user->ID==0) {
			exit('请登陆后再操作');
		}
		$favorite = ggshop_get_user_favorite();
		unset($favorite[$_REQUEST['pid']]);
		update_user_meta( get_current_user_id(), 'ggshop_user_favorite',  json_encode($favorite));
		exit('删除成功');
	}
}

ggshop_redirect_not_login();


$favorite = ggshop_get_user_favorite();
if ($favorite) {
	$posts = query_posts(array(
		'post__in'	=> array_keys($favorite),
		'post_type'	=> 'product',
		'nopaging'	=> true
	));
}

get_header();
?>


	<div class="main user_center_bg">


		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				
				<div class="user_center_title">
					<h3>我的收藏</h3>
					<div class="user_center_cen">
						<div class="shop_pic_list">
							<?php
							if ($favorite): ?>
							<ul class="base-clear">
								<?php
								foreach ($posts as $p) :
									$product = new WC_Product($p);
									$imgsrc = get_the_product_image_html($product); ?>
								<li>
									<span class="remove_fav" data-pid="<?=$product->id?>"><img src="<?bloginfo('template_url')?>/images/icon-remove.png" alt="取消收藏"></span>
									<a class="s_p_l_a" href="<?=$product->get_permalink()?>"><?=$imgsrc?></a>
									<h5><?=wc_price($product->get_price())?></h5>
									<p><?=$product->get_categories() ?><br><?=$product->get_title()?></p>
									<p><a class="s_p_l_btn" href="/cart?add-to-cart=<?=esc_attr($product->id)?>">加入购物车</a></p>
								</li>
								<?php
								endforeach; ?>
							</ul>
							<?php
							else:
								echo '<p><a href="/shop">您还没有任何收藏，快去看看我们的商品吧~</a></p>';
							endif;?>
						</div>
					</div>
				</div>

			</div>

		</div>


	</div>

<style type="text/css">
.remove_fav{
	float: right;
	position: relative;
	right: 20px;
	top: 35px;
	width: 22px;
	cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$('.remove_fav').click(function(event) {
		var request_uri = '/ucp/favorite?ajax=1&act=remove&pid='+$(this).attr('data-pid')
		var e_span = $(this)
		$.get(request_uri, function(data) {
			e_span.parent('li').remove();
			alert(data);
		});
	});
});
</script>
<?php
get_footer();
?>