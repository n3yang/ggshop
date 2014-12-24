<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php if ( ! is_ajax() ) : ?>

			<div class="shop_add">
				<h3 class="shop_add_title">确认购买商品</h3>
<?php endif; ?>

				<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<th width="50px">
							<th colspan="2">商品名称</th>
							<th>商品定价</th>
							<th>优惠价</th>
							<th>购买数量</th>
							<th>小计</th>
						</tr>
						<?php
						do_action( 'woocommerce_review_order_before_cart_contents' );

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<td class="check_set">
							</td>
							<td class="product-thumbnail">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', get_the_product_image_html($_product), $cart_item, $cart_item_key );

									if ( ! $_product->is_visible() )
										echo $thumbnail;
									else
										printf( '<a class="shop_pic" href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
								?>
							</td>
							<td class="product-title">
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
								<s><?php echo ($_product->get_regular_price()); ?></s>
							</td>
							<td class="rmb">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
							</td>
							<td><?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); ?>
							</td>
							<td class="rmb">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</td>
						</tr>

						<?php
							} // end if
						} // end foreach

						do_action( 'woocommerce_review_order_after_cart_contents' );
						?>
					</tbody>

		<tfoot>




		</tfoot>

	</table>

				<table class="table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<th style="text-align:right;">
								购物车金额小计:  <?php wc_cart_totals_subtotal_html(); ?>元 </br>
					</tbody>
				</table>

			</div>

	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

			<div class="shop_add">
				<h3 class="shop_add_title">选择付款方式</h3>
				<div class="shop_add_box">

		<?php if ( WC()->cart->needs_payment() ) : ?>
			<?php
				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				if ( ! empty( $available_gateways ) ) {

					// Chosen Method
					if ( isset( WC()->session->chosen_payment_method ) && isset( $available_gateways[ WC()->session->chosen_payment_method ] ) ) {
						$available_gateways[ WC()->session->chosen_payment_method ]->set_current();
					} elseif ( isset( $available_gateways[ get_option( 'woocommerce_default_gateway' ) ] ) ) {
						$available_gateways[ get_option( 'woocommerce_default_gateway' ) ]->set_current();
					} else {
						current( $available_gateways )->set_current();
					}

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

					if ( ! WC()->customer->get_country() )
						$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
					else
						$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );

					echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';

				}
			?>

		<?php endif; ?>
				</div>
			</div>

			<div class="count_box">
				<table class="table_form" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<th style="text-align:left; vertical-align: top">

							<form class="checkout_coupon" method="post" style="display:none">
								<label for="">激活代金卷</label>
								<input type="text" name="coupon_code" class="form_text" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
								<input class="form_btn" type="submit" name="apply_coupon" value="激活" />
							</form>
							</th>
							<th style="text-align:right;" class="count">
								<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
								<h3>优惠: <em><?php wc_cart_totals_coupon_html( $coupon ); ?></em></h3>
								<?php endforeach; ?>
								<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
								<?php wc_cart_totals_shipping_html(); ?>
								<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
								<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
								<h3>付款金额: <em><?php wc_cart_totals_order_total_html(); ?></em></h3>
								<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
								<p>
									收货人: <span id="shipping_name_reivew"></span>
								</p>
								<p>
									收货地址: <span id="shipping_address_review"></span>
								</p>
							</th>
						</tr>
					</tbody>
				</table>
			</div>


		<div class="btn_wrap" style="text-align:right">

			<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

				<a href="/cart" >
					<img src="<?php bloginfo('template_url') ?>/images/fh_btn.png" alt="">
				</a>


			<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php
			$order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );
			
			echo apply_filters( 'woocommerce_order_button_html', '<input type="image" src="' . get_bloginfo('template_url') . '/images/ok.png" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' );
			?>

			<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) { 
				$terms_is_checked = apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) );
				?>
				<p class="form-row terms">
					<label for="terms" class="checkbox"><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank">terms &amp; conditions</a>', 'woocommerce' ), esc_url( get_permalink( wc_get_page_id( 'terms' ) ) ) ); ?></label>
					<input type="checkbox" class="input-checkbox" name="terms" <?php checked( $terms_is_checked, true ); ?> id="terms" />
				</p>
			<?php } ?>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		</div>

	</div>

	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>

<?php if ( ! is_ajax() ) : ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#shipping_name_reivew').text($('#shipping_first_name').val());
	$('#shipping_address_review').text($('#shipping_address_1').val());

	$('#shipping_first_name, #shipping_address_1').change(function() {
		$('#shipping_name_reivew').text($('#shipping_first_name').val());
		$('#shipping_address_review').text($('#shipping_address_1').val());
	});

	$('.checkout').submit(function() {
		if ($('#shipping_first_name').val()==''){
			alert('请输入收货人姓名');
			return false;
		}
		if ($('#shipping_last_name').val()==''){
			alert('请输入手机号码');
			return false;
		}
		if ($('#shipping_address_1').val()=='' || $('#shipping_address_1').val().length<6){
			alert('请输入详细地址');
			return false;
		}
		if ($('#invoice_box').attr('checked')){
			if ($('#invoice_title').val()=='') {
				alert('请输入发票信息');
				return false;
			};
		}
		return true;
	});

	$('#invoice_box').change(function() {
		if ($('#invoice_box').attr('checked')){
			$('#invoice_title_tr').show();
		} else {
			$('#invoice_title_tr').hide();
		}
	});
	

















	function add_selected_border(){
		$('.shop_add_box input').each(function(i,e) {
			if($(e).checked){
				$(e).next('label').children('img').hide();
				
			}
		});
	}

	$('.shop_add_box input').select(add_selected_border());




});
</script>
	</div>

<?php endif; ?>