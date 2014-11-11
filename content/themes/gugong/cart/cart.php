<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_clear_notices();
// wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>


	<div class="main shop_bg">

		
		<div class="shop_wrap">
			
			<div class="shop_title base-clear">
				<h3>我的购物车</h3>
				<div class="shop_step shop_step1"></div>
			</div>



<form action="/cart" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tr>
						<th width="50px"><input class="check_all" id="all" type="checkbox" /><label class="check_label" for="all">全选</label></th>
						<th>商品名称</th>
						<th>商品定价</th>
						<th>优惠价</th>
						<th>购买数量</th>
						<th>小计</th>
						<th>操作</th>
					</tr>
		<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="check_set">
						<input type="checkbox" >
					</td>

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', wp_get_attachment_image( get_post_thumbnail_id($product_id) ), $cart_item, $cart_item_key );

							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a class="shop_pic" href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>

						<?php
							if ( ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="shop_pic_info" href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );

							// Meta data
							echo WC()->cart->get_item_data( $cart_item );

               				// Backorder notification
               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
						?>
					</td>

					<td class="s">
						<s><?php echo wc_price($_product->get_regular_price()); ?></s>
					</td>

					<td class="product-price rmb">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" size="4" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
						?>
					</td>

					<td class="product-subtotal">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="del">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">删除</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
						?>
					</td>

				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
				</tbody>
			</table>

			<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
				<tbody>
					<tr>
						<th style="text-align:left;">
							购物金额小计 <?php echo WC()->cart->get_cart_subtotal(); ?>元
						</th>
						<th style="text-align:right;" class="del">
							<input type="submit" class="cart-button" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />
							<input type="button" class="cart-button" name="empty_cart" onclick="location.href='/cart?empty-cart'" value="清空购物车" />
						</th>
					</tr>

				</tbody>
			</table>

				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>


		<?php do_action( 'woocommerce_after_cart_contents' ); ?>


<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

			<div class="btn_wrap">
				<a href="/shop" >
					<img src="<?php bloginfo('template_url') ?>/images/goon_shop.png" alt="">
				</a>
				<a href="/checkout" class="">
					<img src="<?php bloginfo('template_url') ?>/images/pay_shop.png" alt="">
				</a>
			</div>




<?php

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => 4,
	'orderby'             => 'post_date DESC',
);
$products = new WP_Query( $args );
if ( $products->have_posts() ) : ?>

			<div class="shop_pic_list">
				<ul class="base-clear">
					<?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
					<li>
						<a class="s_p_l_a" href="<?php the_permalink() ?>"><?php echo get_the_product_image_html( $product ) ?></a>
						<h5><?php echo wc_price($product->get_price()) ?></h5>
						<p><?php the_title() ?></p>
						<p><a class="s_p_l_btn" href="/cart/?add-to-cart=<?php echo get_the_id() ?>">加入购物车</a></p>
					</li>
					<?php endwhile; // end of the loop. ?>
				</ul>
			</div>
<?php endif;
wp_reset_postdata();
?>


<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>


	</div>
</div>


<style>
.quantity input{ width: 50px;}
</style>