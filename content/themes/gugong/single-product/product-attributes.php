<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$attributes = $product->get_attributes();

?>

	<?php
	foreach ( $attributes as $attribute ) :
		if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
			continue;
		}
		echo '<p>';
		echo wc_attribute_label( $attribute['name'] ) . '：';

		if ( $attribute['is_taxonomy'] ) {
			$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
			echo strip_tags(apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ));
		} else {
			// Convert pipes to commas and display values
			$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
			echo strip_tags(apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ));
		}
		echo '</p>';

	endforeach;
	?>
	
