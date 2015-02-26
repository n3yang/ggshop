<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

			<div class="shop_add">
				<h3 class="shop_add_title">确认收货地址</h3>
				<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
				<?php // foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>
				<?php // woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

				<table class="form_table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
					<tbody>
						<tr>
							<td width="100">
								收货人姓名：
							</td>
							<td>
								<input type="text" class="form_text " name="shipping_first_name" id="shipping_first_name" value="<?php echo $checkout->get_value('shipping_first_name');?>">
								<input type="hidden" name="shipping_country" id="shipping_country" value="CN"  class="country_to_state" />
								<input id="ship-to-different-address-checkbox" class="input-checkbox"  type="hidden" name="ship_to_different_address" value="1" />
							</td>
						</tr>
						<tr>
							<td>
								手机号码：
							</td>
							<td>
								<input class="form_text" type="text" name="shipping_last_name" id="shipping_last_name" value="<?php echo $checkout->get_value('shipping_last_name');?>">
							</td>
						</tr>
						<!---
						<tr>
							<td>
								省市区：
							</td>
							<td>
					            <select class="select" name="province" id="s1">
					              <option></option>
					            </select>
					            <select class="select" name="city" id="s2">
					              <option></option>
					            </select>
					            <select class="select" name="town" id="s3">
					              <option></option>
					            </select>
					            <input id="address" name="address" type="hidden" value="" />
							</td>
						</tr>
						-->
						<tr>
							<td>
								详细地址：
							</td>
							<td>
								<input class="form_text" type="text" name="shipping_address_1" id="shipping_address_1" value="<?php echo $checkout->get_value('shipping_address_1');?>" style="width: 400px">
							</td>
						</tr>
						<tr>
							<td>发票信息</td>
							<td class="s"><input type="checkbox" id="invoice_box"> <label for="invoice_box">勾选以填写发票抬头</label></td>
						</tr>
						<tr id="invoice_title_tr" style="display: none">
							<td>发票抬头：</td>
							<td><input name="shipping_company" id="invoice_title" class="form_text" value="<?=$checkout->get_value('shipping_company')?>"></td>
						</tr>
						<!--
						<tr>
							<td>
								
							</td>
							<td>
								<input class="form_btn" type="submit" value="保存新的收货地址" />
							</td>
						</tr>
						-->
					</tbody>
				</table>



				<div>
					<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>
				</div>


					<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

					<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments' ) === 'yes' ) ) : ?>

						<?php if ( ! WC()->cart->needs_shipping() || WC()->cart->ship_to_billing_address_only() ) : ?>

							<h3><?php _e( 'Additional Information', 'woocommerce' ); ?></h3>

						<?php endif; ?>

						<?php foreach ( $checkout->checkout_fields['order'] as $key => $field ) : ?>

							<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

						<?php endforeach; ?>

					<?php endif; ?>

					<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
			</div>
