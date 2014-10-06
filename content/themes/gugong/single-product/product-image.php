<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

$attachment_ids = $product->get_gallery_attachment_ids();

?>


<div class="item_title_left">
	<div class="big_pic">
	<?php

		foreach ( $attachment_ids as $attachment_id ) {

			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
			if ( ! $image )
				continue;
			$image_title = esc_attr( get_the_title( $attachment_id ) );

			$image_single = wp_get_attachment_image_src( $attachment_id );

			echo apply_filters( 'woocommerce_single_product_image_html', 
				sprintf( '%s', $image ), 
				$attachment_id, 
				$post->ID, 
				$image_class );
			break;
		}

	?>

	</div>
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>
</div>
