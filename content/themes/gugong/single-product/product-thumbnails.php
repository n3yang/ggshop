<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $woocommerce;

$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	?>



		
	<div class="small_pic">
		<div class="s_p_w dib-wrap"><?php

		$loop = 0;
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

		foreach ( $attachment_ids as $attachment_id ) {

			// $image_link = wp_get_attachment_url( $attachment_id );



			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			if ( ! $image )
				continue;
			$image_title = esc_attr( get_the_title( $attachment_id ) );

			$image_single = wp_get_attachment_image_src( $attachment_id, 'shop_single');

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', 
				sprintf( '<a href="#" class="small_pic_item dib" title="%s" data-single-pic="%s">%s</a>', $image_title, $image_single[0], $image ), 
				$attachment_id, 
				$post->ID, 
				$image_class );

			$loop++;
		}

	?>
		</div>
	</div>
	<?php
}