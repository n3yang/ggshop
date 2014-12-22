<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
	<div class="main list_bg">

		<div class="list_wrap">
			
			<div class="list_ad">
				
			</div>

			<div class="list_box base-clear">
				<? get_template_part('category-list') ?>
							
				<div class="list_con">
					
					<div class="list_item_wrap">
						<ul>
							<?php 
							while ( have_posts() ) : the_post(); global $product;
							?>
							<li>
								<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
								<a href="<?php the_permalink(); ?>">
									<em>
										<?php the_post_thumbnail('shop_catalog') ?>
									</em>
									<div>
										<p><strong><?php echo ($product->get_price_html()); ?></strong></p>
										<span><?php the_title() ?></span>
									</div>
								</a>
								<?php // do_action( 'woocommerce_after_shop_loop_item' ); ?>
							</li>
							<?php
							endwhile;
							?>
						</ul>

						<!-- <div class="jogger"><a class="prev" href=""> &lt; 上一页</a> <span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a><a href="#?page=6">6</a><a class="next" href="#?page=2">下一页 &gt; </a></div> -->


							<?php 
							ggshop_pagin_nav();
		/*
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
			'base'         => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
			'format'       => '',
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $wp_query->max_num_pages,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
		) ) );
		*/
	?>
					</div>

				</div>


			</div>

		</div>

	</div>

<script>
	
$(function () {
	$(".list_item_wrap li").hover(function () {
		$(this).toggleClass("active")
	})
})

</script>


