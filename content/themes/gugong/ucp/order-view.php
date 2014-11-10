<?php


ggshop_redirect_not_login();

preg_match('/order-view\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches);
if (empty($matches[1])){
	wp_redirect('/shop');
}
$order_id = $matches[1];
$order = new WC_Order($order_id);

$current_user_id = get_current_user_id();
if ($current_user_id != $order->customer_user) {
	wp_redirect('/shop');
}

get_header();
?>

	<div class="main user_center_bg">

		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				
				<div class="user_center_title">
					<h3>我的订单 #<?=$order_id?></h3>
				</div>

				<table class="form_table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<td width="100">
								收货人姓名：
							</td>
							<td>
								<?=$order->shipping_first_name?>
							</td>
						</tr>
						<tr>
							<td>
								手机号码：
							</td>
							<td>
								<?=$order->shipping_last_name?>
							</td>
						</tr>
						<tr>
							<td>
								详细地址：
							</td>
							<td>
								<?=$order->shipping_address_1?>
							</td>
						</tr>
					</tbody>
				</table>

				<div style="margin: 30px"> </div>

				<table class="table1 mct" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<th>商品名称</th>
							<th style="width:90px">优惠价</th>
							<th style="width:35px">数量</th>
							<th style="width:90px">小计</th>
						</tr>
						<?php
						foreach ($order->get_items() as $item){
							$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
							// $img = get_the_product_image_html($_product);
							if ( $_product && $_product->exists() ) {
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td>
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() )
										echo $thumbnail;
									else
										printf( '<a class="shop_pic" href="%s">%s</a>', $_product->get_permalink(), $thumbnail );

									echo $_product->get_title();
								?>
							</td>
							<td class="">
								￥<?=sprintf('%.2f',$_product->get_price())?>
							</td>
							<td>
								x <?=$item['qty']?>
							</td>
							<td class="">
								<?=$order->get_formatted_line_subtotal( $item );?>
							</td>
						</tr>

						<?php
							} // end if
						} // end foreach
						?>
					</tbody>

				<tfoot>
					<td colspan="4" style="text-align: right; background-color: #f7f7f7">订单金额合计：<?=$order->get_formatted_order_total()?></td>
				</tfoot>

			</table>


<form id="order_review" method="post" action="<?=$order->get_checkout_payment_url()?>">

	<div id="payment" class="shop_add_box">
		<?php if ( $order->needs_payment() ) : ?>
		<h3 class="shop_add_title">选择付款方式</h3>
		<ul class="payment_methods methods">
			<?php
				if ( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) {
					// Chosen Method
					if ( sizeof( $available_gateways ) )
						current( $available_gateways )->set_current();

					foreach ( $available_gateways as $gateway ) {
						?>
						<span class="payment_method_<?php echo $gateway->id; ?>">
							<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
							<label for="payment_method_<?php echo $gateway->id; ?>"><?php // echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
							<?php
								if ( $gateway->has_fields() || $gateway->get_description() ) :
									echo '<div class="payment_box payment_method_' . $gateway->id . '" ' . ( $gateway->chosen ? '' : 'style="display:none;"' ) . '>';
									//$gateway->payment_fields();
									echo '</div>';
								endif;
							?>
						</span>
						<?php
					}
				} else {

					echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) . '</p>';

				}
			?>
		</ul>
		<?php endif; ?>

		<div class="form-row" style="text-align: right">
			<?php wp_nonce_field( 'woocommerce-pay' ); ?>
			<?php
				$pay_order_button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );
				
			echo apply_filters( 'woocommerce_order_button_html', '<input type="image" src="' . get_bloginfo('template_url') . '/images/ok.png" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>			
			<input type="hidden" name="woocommerce_pay" value="1" />
		</div>

	</div>

</form>


			</div>

		</div>


	</div>


<?php
get_footer();
?>