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

if (isset($_REQUEST['redirect'])){
	$redirect_url = $_REQUEST['redirect'];
}else{
	$redirect_url = '/shop';
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

					<?php echo open_social_login_html(array('qq'));?>
					<span class="dib">
						<a href="###" onclick="javascript:alert('稍后推出，敬请期待')" title="微博"><img src="<?php bloginfo('template_url')?>/images/weibo.png" alt=""></a>
					</span>
					<span class="dib">
						<a href="###" onclick="javascript:alert('稍后推出，敬请期待')" title="微信"><img src="<?php bloginfo('template_url')?>/images/weixin.png" alt=""></a>
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
						<input type="hidden" name="redirect" value="<?=$redirect_url?>" />
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

	<?php
	} else { // if login, else
	?>


	<div class="main reg_wrap">

		<div class="login_box base-clear">
			
			<div class="login_right">
				
				<h3 style="margin-bottom:20px">
					<a href="/account?login">登录</a>　|　<a class="active" href="/account?reg">注册</a>
				</h3>
			
			<form method="post" class="register" id="form_register" style="width:330px">
			<?php wc_print_notices(); ?>
			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<div class="input_box">
					<input type="text" class="login_name" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" placeholder="用户名" />
				</div>

			<?php endif; ?>

			<div class="input_box">
				<input type="text" class="login_name" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" placeholder="电子邮件地址" />
			</div>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
	
				<div class="input_box">
					<input class="login_password" type="password" name="password" id="reg_password" placeholder="密码" />
				</div>
				<div class="input_box">
					<input class="login_password" type="password" name="password_repeat" id="reg_password_repeat" placeholder="再次输入密码" />
				</div>

			<?php endif; ?>

				<div class="input_box">
					<input class="login_captcha" type="text" name="captcha" id="captcha" placeholder="验证码" />
					<a id="reload_captcha" href="###" title="点击更换"><img id="img_captcha" src="<?=EasyImageCaptchaGetCaptchaUrl()?>" /></a>
				</div>

				<div class="input_box">
					<input type="checkbox" name="agreement" id="agreement" checked="checked" />
					<span class="agreement">我已阅读并接受<a href="#" target="_blank">故宫商城服务条款</a></span>
				</div>


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
	<script type="text/javascript">
	$('#form_register').submit(function() {
		var found_empty = false;
		$('#form_register input[type=text], #form_register input[type=password]').each(function(i,e){
			if ($(this).val()=='' && e.name!='email_2') {
				alert('请将全部输入项填写完整');
				$(this).focus();
				found_empty = true;
				return false;
			}
		});
		if (found_empty) {return false};
		if($('password').val()!=$('#password_repeat').val()){
			alert('两次输入的密码不匹配，请检查');
			return false;
		}
		if(!$('#agreement').attr('checked')){
			alert('请选择并接受我们的服务条款');
			return false;
		}
		return true;
	});
	$('#reload_captcha').click(function() {
		$('#img_captcha').attr('src', $('#img_captcha').attr('src')+'?'+Math.random().toString().substr(2,2));
	});
	</script>
	<?php }// end if login ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
