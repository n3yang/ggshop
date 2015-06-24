<?php


ggshop_redirect_not_login();

if ($_POST['submit'] && wp_verify_nonce($_POST['nonce'], 'chpwd')) {

	$error = '';
	$password = $_POST['password'];

	if (strlen($password)<6) {
		$error = '您输入的新密码小于6位，请重新输入';
	}
	$user = wp_get_current_user();
	if (empty($error) && $user->data->user_pass != wp_hash_password($password)) {
		$error = '您输入的当前密码不正确，请输入正确的密码。';
	}
	
	if (empty($error)){
		wp_set_password($password, $user->ID);
		$message = '修改完成';
	} else {
		$message = $error;
	}

}
// wp_set_password();

get_header();
?>

	<div class="main user_center_bg">


		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				<form method="post">
				<div class="user_center_title">
					<h3>修改密码</h3>
					<div class="user_center_cen">
						<?php echo $message; ?>
						<div class="input_line">
							<label for="">当前密码</label>
							<input type="password" name="password" value="" />
						</div>
						<div class="input_line">
							<label for="">新密码</label>
							<input type="password" name="password_new_retype" value="" />
						</div>
						<div class="input_line">
							<label for="">重复新密码</label>
							<input type="password" name="password_new" value="" />
						</div>
						<div class="input_line">
							<label for=""></label>
							<input type="submit" name="submit" value="确认修改" class="center_btn" />
							<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('chpwd') ?>" />
						</div>

					</div>
				</div>
				</form>

			</div>

		</div>


	</div>



<?php get_footer() ?>