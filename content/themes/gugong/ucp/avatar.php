<?php


ggshop_redirect_not_login();
$current_user = wp_get_current_user();

// 默认头像


$upload_size_limit = isset($wpua_upload_size_limit) ? $wpua_upload_size_limit : 256000;
$upload_size_limit_k = $upload_size_limit/1000;
if ($_POST['submit'] && wp_verify_nonce($_POST['nonce'], 'avatar')) {

	if (!empty($_FILES['wpua-file']['name'])) {
		// $filetype = wp_check_filetype($_FILES['qa_pic']['name']);
		$filetype = wp_check_filetype($_FILES['wpua-file']['name']);
		if (!preg_match('/^image/', $filetype['type'])) {
			$error = '只允许上传jpg、png或gif类型的图片文件';
		} else if ($_FILES['wpua-file']['size'] > $upload_size_limit) {
			$error = '允许上传的图片的尺寸最大为'.$upload_size_limit_k.'K';
		}
		do_action('wpua_update', $current_user->ID);

	} else {
		
		$q = array(
			'post_name'			=> 'system-default-avatar',
			'post_type'			=> 'page',
			'post_status'		=> 'any',
			'posts_per_page'	=> 1,
		);
		$matrix = get_posts($q);

		$q_avatar = array(
			'post_parent'		=> $matrix[0]->ID,
			'post_type'			=> 'attachment',
			'post_status'		=> 'inherit',
			'posts_per_page'	=> -1,
			'order'				=> 'ASC',
		);

		$avatars = get_posts($q_avatar);

		if ($_POST['default_avatar'] == 1){
			$default_avatar = $avatars[0]->ID;
		} else {
			$default_avatar = $avatars[1]->ID;
		}
		update_user_meta($current_user->ID, $wpdb->get_blog_prefix($blog_id).'user_avatar', $default_avatar);
	}
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
							您也可以上传一张自己的jpg、png或gif格式的图片作为头像
						</div>
						<div class="">
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

<script type="text/javascript">
$('#wpua-file').click(function(){
	$('#default_avatar_1, #default_avatar_2').attr('checked', false);
});
</script>

<?php get_footer() ?>