<?php

ggshop_redirect_not_login();

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => 20,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => 'shop_order',
	'post_status' => 'publish'
) ) );


get_header();
?>


	<div class="main user_center_bg">

		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				
				<div class="user_center_title">
					<h3>我的订单</h3>
				</div>


						<table class="table2" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
							<tbody>
								<tr>
									<th width="60%">商品</th>
									<th width="23%">订单总额</th>
									<th>
<!-- 										<select name="" id="">
											<option value="">全部订单</option>
											<option value="">已审核</option>
											<option value="">已完成</option>
											<option value="">已取消</option>
										</select> -->
									</th>
									<th>操作</th>
								</tr>
							</tbody>
						</table>

					<?php
					foreach ( $customer_orders as $customer_order ) {
						$order = new WC_Order();

						$order->populate( $customer_order );

						$status     = get_term_by( 'slug', $order->status, 'shop_order_status' );
						$item_count = $order->get_item_count();


						?>
						<table class="table1 mct_t" style="margin-bottom:0" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
							<tbody>
								<tr>
									<th widht="60%"><img src="" alt="">订单号:<span><?php echo $order->get_order_number(); ?></span><em>收货人：<span><?=$order->shipping_first_name?></span></em></th>
									<th>下单时间：<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?></th>
								</tr>
							</tbody>
						</table>
						<table class="table1 mct" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
							<tbody>
								<tr>
									<td width="60%">
										<?php
										foreach ($order->get_items() as $item):
											$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
											$img = get_the_product_image_html($_product);
										?>
										<a class="shop_pic" href="<?=$_product->get_permalink()?>" target="_blank"><?=$img?></a>
										<a class="shop_pic_info" href="<?=$_product->get_permalink()?>" target="_blank"><?=strip_tags($_product->get_categories()) ?><br>
											<?=$_product->get_title()?></a>
										<p style="padding: 4px"></p>
										<?php
										endforeach; ?>
									</td>
									<td class="rmb">
										￥<?=sprintf('%.2f',$order->get_order_total())?><br>
										<span>
											在线支付
										</span>
									</td>
									<td class="dd">
										<span class="through"><?php echo ucfirst( __( $status->name, 'woocommerce' ) ); ?></span><br>

							<?php
							$actions = array();

							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['pay'] = array(
									'url'  => '/ucp/order-view/'.$order->id.'#payment',
									// 'url'  => $order->get_checkout_payment_url(),
									'name' => __( 'Pay', 'woocommerce' )
								);
							}
/*
							if ( in_array( $order->status, apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
								$actions['cancel'] = array(
									'url'  => $order->get_cancel_order_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
									'name' => __( 'Cancel', 'woocommerce' )
								);
							}
*/
							$actions['view'] = array(
								'url'  => str_replace('account/view-order', 'ucp/order-view', $order->get_view_order_url()),
								'name' => '订单详情'
							);

							$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

							if ($actions) {
								foreach ( $actions as $key => $action ) {

									echo '<em><a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a></em><br>';
								}
							}
						?>
									</td>
								</tr>
							</tbody>
						</table>
				<?php
				} ?>

			</div>

		</div>


	</div>


<?php get_footer() ?>