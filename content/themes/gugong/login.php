<?php
/**
 * Template Name: Login Page
 * The template for pinwu login & registration page
 * 
 * @package WordPress
 * @subpackage Pinwu
 */

// do logout
if (isset($_REQUEST['act']) && $_REQUEST['act']=='out') {
	wp_logout();
	exit('<script type="text/javascript" charset="utf-8">alert("退出成功");location.href="/"</script>');
}

// do login
if (isset($_POST['login_submit'])) {
	$creds = array();
	$creds['user_login'] = $_POST['login_username'];
	$creds['user_password'] = $_POST['login_password'];
	$creds['remember'] = ($_POST['auto_login']=='1') ? true : false;
	$result = wp_signon($creds);
	if (is_wp_error($result)) {
		$login_error = preg_replace('/<a(.*)<\/a>？/', '', $result->get_error_message());
	} else {
		exit('<script type="text/javascript" charset="utf-8">alert("登陆成功");location.href="/"</script>');
	}
}

// do sign up
if (isset($_POST['signup_submit'])) {
	$error = '';
	if (empty($_POST['username'])){
		$error = '请输入用户名';
	} else if (!preg_match('/^[a-zA-Z0-9\_]+$/i', $_POST['username'])) {
		$error = '用户名只能输入英文和数字';
	} else if (empty($_POST['password'])) {
		$error = '请输入密码';
	} else if ($_POST['r_password']!=$_POST['password']){
		$error = '两次输入的密码不一样';
	} else if (empty($_POST['email']) ) {
		$error = '请输入正确的Email地址';
	} else if (username_exists( $_POST['username'])){
		$error = '用户名已经存在';
	} else if (email_exists($_POST['email'])) {
		$error = '邮件地址已存在';
	}
	if (!$error){
		$user_id = wp_create_user( $_POST['username'], $_POST['password'], $_POST['email'] );
		if ($user_id && !empty($_POST['tel'])) {
			update_user_meta($user_id, 'tel', $_POST['tel']);
			$creds = array();
			$creds['user_login'] = $_POST['username'];
			$creds['user_password'] = $_POST['password'];
			$creds['remember'] = false;
			$result = wp_signon($creds);
			exit('<script type="text/javascript" charset="utf-8">alert("注册成功");location.href="/"</script>');
		} else {
			exit('<script type="text/javascript" charset="utf-8">alert("注册失败");location.href="/login"</script>');
		}
	} else {
		exit('<script type="text/javascript" charset="utf-8">alert("'.$error.'");history.back(1);</script>');
	}
}
?>
<?php get_header(); ?>


	<!--Crumbs-->
	<div class="crumbs base-clear">
		<div class="crumbs-list">
			<span>
				<a href="/" class="home-icon">首页</a>
			</span>
			<span>
				<a href="#">用户登录</a>
			</span>
		</div>
	</div>
	<!--/Crumbs-->
	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style/login.css"/>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/happy.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/happy.methods.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/login.js"></script>
	
	
	<!--login & register-->
	<div class="login-wrap base-clear">
		<!--login-->
		<div class="login-box">
		
			<div class="login-form-wrap">
				<h1><img src="<?php bloginfo('template_url'); ?>/images/login-title.gif" /></h1>
				<?php
				if (!empty($login_error)){
				?>
				<div class="error-box"><p>♦<?php echo $login_error ?></p></div>
				<?php } ?>
				<form id="login" method="post" action="">
					<div class="form-line">
						<label for="login_username">用户名：</label>
						<input id="login_username" name="login_username" class="ui-input u" type="text" />
					</div>
					<div class="form-line">
						<label for="login_password">密码：</label>
						<input id="login_password" name="login_password" class="ui-input p" type="password" autocomplete="off" />
					</div>
					<!--
					<div class="form-line">
						<label for="v_code">验证码：</label>
						<input id="v_code" name="v_code" class="ui-input w-80" type="text" />
						<span class="pic-code"><a href="#"><img src="<?php bloginfo('template_url'); ?>/images/image.jpg" /></a></span>
						<span class="pic-code"><a href="#">换一张</a></span>
					</div>
					-->
					<div class="form-line m-195">
						<input id="auto_login" name="auto_login" type="checkbox" class="ui-check" value="1">
						<label for="auto_login" class="ui-check-label">自动登录</label>
					</div>
					<div class="form-line m-195">
						<input class="form-btn" id="login_btn" name="login_submit" type="submit" value="立即登录" />
					</div>
				</form>
			</div>
			
			<div class="login-merit">
				<p>如果您不是会员，请注册</p>
				<p><strong>友情提示：</strong></p>	
				<p>不注册会员也能够在本站预约免费量尺设计，但注册会员之后您还可以：</p>
				<p><i>1</i> 发布咨询问题&nbsp;&nbsp;&nbsp;&nbsp;<i>2</i> 预约免费测量&nbsp;&nbsp;&nbsp;&nbsp;<i>3</i> 获得新品推荐</p>
				<p><i>4</i> 保存个人资料</p>
			</div>
			
		</div>
		<!--/login-->
		<!--register-->
		<div class="register-box">
			<div class="reg-form-wrap">
				<h1><img src="<?php bloginfo('template_url'); ?>/images/reg-title.gif" /></h1>
				<form id="reg" method="post" action="">
					<div class="form-line">
						<label for="username">用户名：</label>
						<input id="username" name="username" class="ui-input u" type="text" />
						<p class="input-info">用户名只能由英文字母a～z、数字0～9组成</p>
					</div>
					<div class="form-line">
						<label for="password">密码：</label>
						<input id="password" name="password" class="ui-input p" type="password" autocomplete="off" />
						<p class="input-info">密码长度6～20位，由英文字母a～z和数字0～9混合组成</p>
					</div>
					<div class="form-line">
						<label for="r_password">确认密码：</label>
						<input id="r_password" name="r_password" class="ui-input p" type="password" autocomplete="off" />
					</div>
					<div class="form-line">
						<label for="email">电子邮箱：</label>
						<input id="email" name="email" class="ui-input" type="text" />
					</div>
					<div class="form-line">
						<label for="tel">手机号码：</label>
						<input id="tel" name="tel" class="ui-input" type="text" />
					</div>
					<!--
					<div class="form-line">
						<label for="v_code_reg">验证码：</label>
						<input id="v_code_reg" name="v_code" class="ui-input w-80" type="text" />
						<span class="pic-code"><a href="#"><img src="<?php bloginfo('template_url'); ?>/images/image.jpg" /></a></span>
						<span class="pic-code"><a href="#">换一张</a></span>
					</div>
					
					<div class="form-line">
						<label for="order">预约免费量尺：</label>
						<input id="order" name="order" type="checkbox" class="ui-check" value="1">
						<label for="order" class="ui-check-label">购买家具前，预先看到家具摆到自家效果，免费上门量房设计。</label>
					</div>
					-->
					
					<div class="form-line m-195">
						<input id="term" name="term" type="checkbox" class="ui-check" value="1" checked="checked">
						<label for="term" class="ui-check-label">我已经阅读并同意注册条款</label>
					</div>
					<div class="form-line m-195">
						<input class="form-btn" id="login_btn" name="signup_submit" type="submit" value="立即注册" />
					</div>
				</form>
			</div>
		</div>
		<!--/register-->
	</div>
	<!--/login & register-->

<?php get_footer(); ?>