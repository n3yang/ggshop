<?php
/*
 * Plugin Name: BOC pay with socket For WooCommerce
 * Plugin URI: http://n3yang.com
 * Description: 
 * Version: 1.0
 * Author: n3yang
 * Author URI: http://n3yang.com
 * Requires at least: 3.8.1
 * Tested up to: 3.9.2
 *
 * Text Domain: bocpay
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wc_bocpay_gateway_init() {

    if( !class_exists('WC_Payment_Gateway') )  return;

    require_once( plugin_basename( 'class-wc-bocpay.php' ) );

    add_filter('woocommerce_payment_gateways', 'woocommerce_bocpay_add_gateway' );

}
add_action( 'plugins_loaded', 'wc_bocpay_gateway_init' );

/**
 * Add the gateway to WooCommerce
 *
 * @access  public
 * @param   array $methods
 * @package WooCommerce/Classes/Payment
 * @return  array
 */
function woocommerce_bocpay_add_gateway( $methods ) {

    $methods[] = 'WC_Bocpay';
    return $methods;
}

/**
 * Display bocpay Trade No. for customer
 */
function wc_bocpay_display_order_meta_for_customer( $total_rows, $order ){
    $my_custom_field_1 = get_post_meta( $order->id, 'bocpay_trade_no', true );
    $new_total_rows = array();
    if( !empty($my_custom_field_1) ){
        $new_row['bocpay_trade_no'] = array(
            'label' => '交行交易编号',
            'value' => $my_custom_field_1
        );
        // Insert $new_row after shipping field
        $total_rows = array_merge( array_splice( $total_rows,0,2), $new_row, $total_rows );
    }
    return $total_rows;
}
add_filter( 'woocommerce_get_order_item_totals', 'wc_bocpay_display_order_meta_for_customer', 10, 2 );


