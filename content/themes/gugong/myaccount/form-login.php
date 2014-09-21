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

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>


<div class="main login_wrap">
		<div class="nav">
			<ul>
				<li><a class="nav1" href="#"></a></li>
				<li class=""><a class="nav2" href="#"></a></li>
				<li><a class="nav3" href="#"></a></li>
				<li class="c_nav"></li>
				<li><a class="nav4" href="#"></a></li>
				<li><a class="nav5" href="#"></a></li>
				<li><a class="nav6" href="#"></a></li>
			</ul>
		</div>
		
		
		<div class="login_box base-clear">
			
			<div class="login_left">
				<div class="dib-wrap">
					<span class="dib">
						<a href="#" title="微博"><img src="images/weibo.png" alt=""></a>
					</span>
					<span class="dib">
						<a href="#" title="腾讯QQ"><img src="images/qq.png" alt=""></a>
					</span>
					<span class="dib">
						<a href="#" title="微信"><img src="images/weixin.png" alt=""></a>
					</span>
				</div>
				<h3>登录帐号后，您已同意用户条款！</h3>
			</div>
			<div class="login_right">
				
				
				<h3 style="margin-bottom:30px">
					<a class="active" href="login.html">登录</a>　|　<a href="reg.html">注册</a>
				</h3>

				<?php do_action( 'woocommerce_login_form_start' ); ?>
				<form method="post" class="login">
					
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
						<input class="login_btn" type="submit" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" /> 
					</div>
					<div class="input_box">
						<?php wp_nonce_field( 'woocommerce-login' ); ?>
						<a class="login_info" href="/account/lost-password">忘记密码</a>
						<a class="login_info" href="/account">还没有商城账号，请从这里开始！</a>
					</div>
				<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>

			</div>

		</div>

	</div>


		<h2><?php _e( 'Login', 'woocommerce' ); ?></h2>

		

			


	<div class="col-2">

		<h2><?php _e( 'Register', 'woocommerce' ); ?></h2>

		<form method="post" class="register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="form-row form-row-wide">
					<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>

			<p class="form-row form-row-wide">
				<label for="reg_email"><?php _e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
	
				<p class="form-row form-row-wide">
					<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password" id="reg_password" />
				</p>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="left:-999em; position:absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'register' ); ?>
				<input type="submit" class="button" name="register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>


<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
