<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>


	<?php
	if (isset($_GET['login'])){
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
				<?php wc_print_notices(); ?>
				<form method="post" class="login">
				<?php do_action( 'woocommerce_login_form_start' ); ?>
					
					<div class="input_box">
						<input  class="login_name" type="text" class="input-text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>"  placeholder="用户名" />
					</div>
					<div class="input_box">
						<input class="login_password" type="password" name="password" id="password" autocomplete="off" placeholder="输入您的密码" />
					</div>
					<?php do_action( 'woocommerce_login_form' ); ?>
					<div class="input_box">
						<input class="login_check" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label class="login_check_label" for="rememberme">记住密码</label>
					</div>
					<div class="input_box">
						<input class="login_btn" type="submit" name="login" value=" " /> 
						<input type="hidden" name="redirect" value="/shop" ?>
					</div>
					<div class="input_box">
						<?php wp_nonce_field( 'woocommerce-login' ); ?>
						<a class="login_info" href="#/account/lost-password">忘记密码</a>
						<a class="login_info" href="/account">还没有商城账号，请从这里开始！</a>
					</div>
				<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>

			</div>

		</div>

	</div>

	<?php
	} else { // if login, else
	?>


	<div class="main reg_wrap">

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
					<a href="/account?login">登录</a>　|　<a class="active" href="/account?reg">注册</a>
				</h3>
			<?php wc_print_notices(); ?>
			<form method="post" class="register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<div class="input_box">
					<input type="text" class="login_name" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" placeholder="用户名" />
				</div>

			<?php endif; ?>

			<div class="input_box">
				<input type="email" class="login_name" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" placeholder="电子邮件地址" />
			</div>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
	
				<div class="input_box">
					<input class="login_password" type="password" name="password" id="reg_password" placeholder="密码" />
				</div>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="left:-999em; position:absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<div class="input_box">
				<?php wp_nonce_field( 'woocommerce-register', 'register' ); ?>
				<input type="submit" class="reg_btn" name="register" value=" " />
			</div>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

			</div>

		</div>

	</div>
	<?php }// end if login ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
