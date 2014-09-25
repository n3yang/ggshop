<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// wc_print_notices(); ?>

<div style="height: 300px;text-align: center; padding-top: 100px">
	当前登陆用户：<?php echo $current_user->display_name; ?>，
	<a href="<?php echo wp_logout_url( get_permalink( wc_get_page_id( 'shop' ) ) );?>" >退出</a>
</div>

<?php do_action( 'woocommerce_before_my_account' ); ?>

<?php // wc_get_template( 'myaccount/my-downloads.php' ); ?>

<?php // wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

<?php // wc_get_template( 'myaccount/my-address.php' ); ?>

<?php do_action( 'woocommerce_after_my_account' ); ?>
