<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }

	 global $product;
?>

	<div class="main item_bg">

		<div class="item_wrap">
			
			<div class="list_ad" style="height: 0">

			</div>
			<div class="item_box">

				<h3 class="crumbs">
					<a href="/shop">商城首页</a> &gt;&gt; <a href="#"><?php echo $product->get_categories();?></a> &gt;&gt; <a href="#"><?php the_title() ?></a> 
				</h3>

				<div class="item_title base-clear">
					<?php
						/**
						 * woocommerce_before_single_product_summary hook
						 *
						 * @hooked woocommerce_show_product_sale_flash - 10
						 * @hooked woocommerce_show_product_images - 20
						 */
						do_action( 'woocommerce_before_single_product_summary' );
					?>
					<div class="item_title_right">
					<?php
						/**
						 * woocommerce_single_product_summary hook
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 */
						// do_action( 'woocommerce_single_product_summary' );
					?>	
					<?php

						if ($product->product_type=='variable'){
							echo '<h3><div class="single_variation_price">'.$product->get_price_html().'</div><h3>';
						} else {
							if ($product->get_sale_price()) {
								echo '<h3><s style="color:gray">原价：'.wc_price($product->get_regular_price()).'</s></h3>';
								echo '<h2>优惠价：'.wc_price($product->get_sale_price()).'</h2>';
							} else {
								echo '<h2>'.wc_price($product->get_price()).'</h2>';
							}
						}
					?>
						
						<h3><?php echo $product->get_categories(); ?></h3>
						<h4><?php the_title() ?></h4>

						<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>

						<?php $product->list_attributes(); ?>

						<?php woocommerce_template_single_add_to_cart();?>

					</div><!-- .summary -->
				</div>
			<div class="itme_info">
				<?php the_content(); ?>
			</div>
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		// do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />


				<?php
				$related = $product->get_related( 8 );

				if ( sizeof( $related ) > 0 ) {
				$args = apply_filters( 'woocommerce_related_products_args', array(
					'post_type'            => 'product',
					'ignore_sticky_posts'  => 1,
					'no_found_rows'        => 1,
					'posts_per_page'       => 4,
					'orderby'              => $orderby,
					'post__in'             => $related,
					'post__not_in'         => array( $product->id )
				) );

				$products = new WP_Query( $args );
				?>
				<div style="text-align: center;">
					<img src="<?php bloginfo('template_url') ?>/images/ather.png" alt="">
				</div>
				<div class="shop_pic_list">
					<ul class="base-clear">
						<?php while ( $products->have_posts() ) : $products->the_post(); global $product;?>
						<li>
							<a class="s_p_l_a" href="<?php the_permalink(); ?>"><?php echo get_the_product_image_html( $product ) ?></a>
							<h5><?php echo wc_price($product->get_price());?></h5>
							<p><?php the_title() ?></p>
							<p><a class="s_p_l_btn" href="/cart?add-to-cart=<?php echo esc_attr( $product->id ); ?>">加入购物车</a></p>
						</li>
						<?php endwhile; ?>
					</ul>
				</div>
				<?php 
				} // end if 
				wp_reset_postdata();
				?>
			<!-- #product-<?php the_ID(); ?> -->

				<span id="back-to-top"></span>
			</div>
		</div>

	</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#submit_to_cart').click(function() {
		if ($('#variation_id').val()=='') {
			var labelstring = '';
			$('table.variations label span').each(function(){
				labelstring += labelstring=='' ? $(this).text() : '、' + $(this).text();
			});
			alert('请选择' + labelstring);
			return false;
		}
		$.blockUI({
			message: '正在加入购物车，请稍后⋯⋯'
		});
		return true;
	});
	$('#add_favorite').click(function(event) {
		var request_uri = '/ucp/favorite?ajax=1&act=add&pid='+$(this).attr('data-pid')
		$.get(request_uri, function(data) {
			alert(data);
		});
		
	});

	$(function() {
		$(window).scroll(function(){
			if ($(window).scrollTop()>100){
				$("#back-to-top").fadeIn(500);
				$("#back-to-top").css('right', ($(window).width()-1280)/2+80);
			} else {
				$("#back-to-top").fadeOut(500);
			}
		});
		$("#back-to-top").click(function(){
			$('body,html').animate({scrollTop:0},600);
			return false;
		});
	});
});
</script>

<?php do_action( 'woocommerce_after_single_product' ); ?>