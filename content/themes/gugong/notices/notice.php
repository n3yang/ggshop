<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>

<div class="ggshop-message">
    <button type="button" class="close">×</button>
    <?php foreach ( $messages as $message ) : ?>
    <?php echo wp_kses_post( $message ); ?>
    <?php endforeach; ?>
</div>