<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// wc_print_notices();

?>



	<div class="main shop_bg">

		
		<div class="shop_wrap">
			
			<div class="shop_title base-clear">
				<h3>我的购物车</h3>
				<div class="shop_step shop_step1"></div>
			</div>

			<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
				<tbody>
					<tr>
						<th></th>
					</tr>
					<tr>
						<td class="check_set" style="height:100px;">
							<p class="cart-empty"><?php _e( 'Your cart is currently empty.', 'woocommerce' ) ?></p>

							<?php do_action( 'woocommerce_cart_is_empty' ); ?>

						</td>
					</tr>
				</tbody>
			</table>

			<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
				<tbody>
					<tr>
						<th style="text-align:left;">
						</th>
					</tr>
				</tbody>
			</table>

			<div class="btn_wrap">
				<a href="/shop" >
					<img src="<?php bloginfo('template_url') ?>/images/goon_shop.png" alt="">
				</a>
			</div>

		</div>

	</div>