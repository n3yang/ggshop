<?php
/**
 * Displayed when no products are found matching the current query.
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/no-products-found.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

	<div class="main list_bg">

		<div class="list_wrap">
			
			<div class="list_ad">
				
			</div>

			<div class="list_box base-clear">
				<?php get_template_part('category-list') ?>
							
				<div class="list_con" style="text-align: center">
					<?php
					// if (get_query_var('product_cat')=='presales') {
					// 	echo '<img src="'.get_bloginfo('template_url').'/images/presales-category-block.jpg" />';
					// } else {
						echo '<h3>没有找到符合要求的产品</h3>';
					// }
					?>
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