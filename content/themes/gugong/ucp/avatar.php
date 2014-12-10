<?php


ggshop_redirect_not_login();

if ($_POST['submit'] && wp_verify_nonce($_POST['nonce'], 'avatar')) {

	var_dump($wp_user_avatar);

	$wp_user_avatar->wpua_action_process_option_update($current_user->ID);

	echo get_wp_user_avatar();
	
}


get_header();
?>

	<div class="main user_center_bg">


		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				<form method="post" enctype="multipart/form-data">
				<div class="user_center_title">
					<h3>设置头像</h3>
					<div class="user_center_cen">
						<?php echo $message; ?>
						您可以选择其中一张作为头像，也可以上传一张自己的图片。
						<div class="input_line">
							<input type="radio" name="default_avatar" id="default_avatar_1" value="1" />
							<label for="default_avatar_1"><img src="<? bloginfo('template_url') ?>/images/avatar-zhuang.jpg" />壮壮</label>
						</div>
						<div class="input_line">
							<input type="radio" name="default_avatar" id="default_avatar_2" value="2" />
							<label for="default_avatar_2"><img src="<? bloginfo('template_url') ?>/images/avatar-mei.jpg" />美美</label>
						</div>
						
						<div class="input_line">
							您也可以上传一张自己的图片作为头像
						</div>
						<div>
							<label for="wpua-file">选择图片：</label>
							<input type="file" name="wpua-file" id="wpua-file" />
						</div>
						<div class="input_line">
							<label for=""></label>
							<input type="submit" name="submit" value="确认修改" class="center_btn" />
							<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('avatar') ?>" />
							<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($current_user->ID); ?>" />
						</div>

					</div>
				</div>
				</form>

			</div>

		</div>


	</div>



<?php get_footer() ?>