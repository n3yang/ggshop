<?php


if ($_REQUEST['ajax'] && $_REQUEST['act']=='add' && !empty($_REQUEST['pid'])) {
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

ggshop_redirect_not_login();


$favorite = ggshop_get_user_favorite();
if ($favorite) {
	$posts = query_posts(array(
		'post__in'	=> array_keys($favorite),
		'post_type'	=> 'product'
	));
}
get_header();
?>


	<div class="main user_center_bg">


		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				
				<div class="user_center_title">
					<h3>我的最爱</h3>
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
								echo '<p><a href="/shop">您还没有任何收藏，快去浏览我们的商品吧~</a></p>';
							endif;?>
						</div>
					</div>
				</div>

			</div>

		</div>


	</div>


<?php
get_footer();
?>