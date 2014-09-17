<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		n3yang
 * @package 	GugongShop
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );


		if (is_tax()){
			if ( have_posts() ) {
				do_action( 'woocommerce_before_shop_loop' );
				woocommerce_product_loop_start();
				woocommerce_product_subcategories();
				while ( have_posts() ) : the_post(); 
					 wc_get_template_part( 'content', 'product' );
				endwhile; // end of the loop.
				woocommerce_product_loop_end();
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				wc_get_template( 'loop/no-products-found.php' );
			}
			$term = get_queried_object();
			
		} else {
			get_template_part('content', 'shop');
		}

		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

<?php get_footer( 'shop' ); ?>