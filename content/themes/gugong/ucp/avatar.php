<?php


ggshop_redirect_not_login();

if ($_POST['submit'] && wp_verify_nonce($_POST['nonce'], 'avatar')) {

	
	
}


get_header();
?>

	<div class="main user_center_bg">


		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				<form method="post">
				<div class="user_center_title">
					<h3>设置头像</h3>
					<div class="user_center_cen">
						<?php echo $message; ?>
						您可以选择其中一张作为头像，也可以上传一张自己的图片。
						<div class="input_line">
							<label for="">当前密码</label>
							<input type="password" name="password" value="" />
						</div>
					<?php echo get_wp_user_avatar();?>
					<?php echo do_shortcode('[avatar_upload]');?>
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
							<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('avatar') ?>" />
						</div>

					</div>
				</div>
				</form>

			</div>

		</div>


	</div>



<?php get_footer() ?>