<?php
/**
 * Lost password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>


    <div class="main login_wrap">

        <div class="login_box base-clear">
            
            <div class="login_left">
                <div class="dib-wrap">
                    <span class="dib">
                        <a href="#" title="微博"><img src="<?php bloginfo('template_url')?>/images/weibo.png" alt=""></a>
                    </span>
                    <span class="dib">
                        <a href="#" title="腾讯QQ"><img src="<?php bloginfo('template_url')?>/images/qq.png" alt=""></a>
                    </span>
                    <span class="dib">
                        <a href="#" title="微信"><img src="<?php bloginfo('template_url')?>/images/weixin.png" alt=""></a>
                    </span>
                </div>
                <h3>登录帐号后，您已同意用户条款！</h3>
            </div>
            <div class="login_right">

                <h3 style="margin-bottom:30px">
                    <a class="active" href="/account?login">登录</a>　|　<a href="/account?reg">注册</a>
                </h3>

                <h4><?php wc_print_notices(); ?></h4>
<form method="post" class="lost_reset_password">

	<?php if( 'lost_password' == $args['form'] ) : ?>

        <p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p>

        <div class="input_box">
            <input class="login_name" type="text" name="user_login" id="user_login" placeholder="用户名或邮箱" />
        </div>

	<?php else : ?>

        <div class="input_box">
            <?php echo apply_filters( 'woocommerce_reset_password_message', __( 'Enter a new password below.', 'woocommerce') ); ?>
        </div>
        <div class="input_box">
            <input type="password" class="login_password" name="password_1" id="password_1" placeholder="<?php _e( 'New password', 'woocommerce' ); ?>" />
        </div>
        <div class="input_box">
            <input type="password" class="login_password" name="password_2" id="password_2" placeholder="<?php _e( 'Re-enter new password', 'woocommerce' ); ?>" />
        </div>

        <input type="hidden" name="reset_key" value="<?php echo isset( $args['key'] ) ? $args['key'] : ''; ?>" />
        <input type="hidden" name="reset_login" value="<?php echo isset( $args['login'] ) ? $args['login'] : ''; ?>" />

    <?php endif; ?>

    <div class="clear"></div>

    <p class="form-row"><input type="submit" class="button" name="wc_reset_password" value="<?php echo 'lost_password' == $args['form'] ? __( 'Reset Password', 'woocommerce' ) : __( 'Save', 'woocommerce' ); ?>" /></p>
	<?php wp_nonce_field( $args['form'] ); ?>

</form>



            </div>

        </div>

    </div>