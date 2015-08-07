<?


function ggshop_upload_bits($filename, $filecontent){

	$uploadrs = wp_upload_bits( $filename, null, $filecontent );
	$wp_upload_dir = wp_upload_dir();
	$filetype = wp_check_filetype($uploadrs['file']);
	$attachment = array(
		'guid'				=> $wp_upload_dir['url'] . '/' . basename($uploadrs['file']),
		'post_mime_type'	=> $filetype['type'],
		'post_title'		=> 'diy-shell-' . preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'		=> '',
		'post_status'		=> 'inherit'
	);
	$attach_id = wp_insert_attachment($attachment, $uploadrs['file']);
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $uploadrs['file'] );
	wp_update_attachment_metadata( $attach_id,  $attach_data );

	return array(
		'id'	=> $attach_id,
		'url'	=> $uploadrs['url']
	);
}


if ($_POST['submit'] && wp_verify_nonce($_POST['nonce'], 'shell')) {

	$error = '';

	if (!empty($_FILES['upload']['name'])) {
		$filetype = wp_check_filetype($_FILES['upload']['name']);
		if (!preg_match('/^image/', $filetype['type'])) {
			$error = '只允许上传jpg、png或gif类型的图片文件';
		} else if ($_FILES['upload']['size'] > 8200000) {
			$error = '允许上传的图片的尺寸最大为8MB';
		} else if (empty($_POST['preview'])) {
			$error = '请先预览效果后再提交';
		}
	}

	if (!empty($error)) {
		$message = $error;
	} else {

		$filename = $_FILES['upload']['name'];
		$filecontent = file_get_contents($_FILES['upload']['tmp_name']);
		$image_orginal = ggshop_upload_bits($filename, $filecontent);

		$path_parts = pathinfo($filename);
		$filename_preview = $path_parts['filename'] . '-preview.' . $path_parts['extension'];
		$filecontent_preview = base64_decode(preg_replace('/data:image\/png;base64,/', '', $_POST['preview']));
		$image_preview= ggshop_upload_bits($filename_preview, $filecontent_preview);

		$posts = get_posts(array('name'=>'diy-shell', 'post_type'=>'product'));
		$product = new WC_Product($posts[0]);
		$variation = array(
			'orginal'  => $image_orginal['url'],
			'preview'  => $image_preview['url'],
			'phone'    => sanitize_title($_POST['phone'])
		);
		$rs = WC()->cart->add_to_cart( $product->id, 1, '', $variation );
		if ($rs) {
			wp_redirect('/cart');
		} else {
			$message = '貌似在添加购物车的过程中出现了一些问题，请重新登陆后再试。';
		}
	}
}



require 'header.php' ?>


	<?php if ($message):?>
	<div class="ggshop-message"><button type="button" class="close">×</button><?php echo $message; ?></div>
	<?php endif; ?>

	<div class="main item_bg">

		<div class="item_wrap">
			
			<div class="list_ad" style="height: 0">

			</div>
			<div class="item_box">

				<h3 class="crumbs">
					<a href="/shop">商城首页</a> &gt;&gt; <a href="#">定制手机壳</a> 
				</h3>

				<div class="item_title base-clear">
					<div class="item_title_right">
						<h4>定制手机壳</h4>
					</div>
				</div>
				<div class="itme_info">

					<div id="cover-container">
						<form id="cover-form" action="" method="post" enctype="multipart/form-data">
						
							<div class="step-1">
								<div>
									<div class="custom">
										<h4>请选择手机型号：</h4>
										<select id="phone" name="phone">
											<option value="iPhone5" selected="selected">iPhone5</option>
										</select>
									</div>
									<div class="custom">
										<h4>请选择背景图片：</h4>
										<input class="center_btn" id="cover-upload" name="upload" type="file" />
									</div>
									<div class="custom">
										<h4>提示：</h4>1.请上传分辨率大于1200x700像素的图片以保证制作效果；2.目前支持的图片格式有：JPG、JPEG、PNG等。
										</ul>
									</div>
								</div>

								<div id="cover-view">
									<img src="" alt="">
								</div>

								<div id="btn-bar">
									<button class="cover-btns center_btn" id="btn-zoom-in" data-method="zoom" data-option="0.1" type="button">放大</button>
									<button class="cover-btns center_btn" id="btn-zoom-out" data-method="zoom" data-option="-0.1" type="button">缩小</button>
									<button class="cover-btns center_btn" id="btn-rotate-right" data-method="rotate" data-option="-90" type="button">逆时针旋转</button>
									<button class="cover-btns center_btn" id="btn-rotate-left" data-method="rotate" data-option="90" type="button">顺时针旋转</button>
									<div style="display: inline-block; float: right"><button class="center_btn" id="btn-preview" type="button">预览</button></div>
								</div>
								
							</div>

							<div class="step-2">
								<div><h3>预览效果：</h3></div>
								<div id="cover-preview"></div>
								<div style="text-align: center">
									<button class="center_btn" type="button" id="back-step-1">返回修改</button>
									<input class="center_btn" id="submit-to-cart" name="submit" value="加入购物车" type="submit" />
									<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('shell') ?>" />
									<input id="preview-data" name="preview" type="hidden" />
								</div>
							</div>

						</form>
					</div>

				</div>

			</div>
		</div>

	</div>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/cropper.css" />
<script src="<?php bloginfo('template_url'); ?>/js/cropper.js"></script>
<script type="text/javascript">

$(function(){

	// init
	var msgBrowserIsLow = '您的浏览器版本过低，不支持定制，如果浏览器支持急速模式，请切换到急速模式。'
	var msgUploadFileError = '请上传图片文件，目前支持的图片格式有：JPG、JPEG、PNG等。'
	var msgUploadFileFirst = '请先选择图片进行预览'
	
	var $cover = $('#cover-view > img');
	var coverIsActive = false;

	var support = {
		fileList: !!$('<input type="file">').prop('files'),
		blobURLs: !!window.URL && URL.createObjectURL,
		formData: !!window.FormData
	}
	support.datauri = support.fileList && support.blobURLs

	if (!support.formData) {
		alert(msgBrowserIsLow);
	}

	$('.step-2, #cover-view, #btn-bar').hide();

	// listener
	$('#cover-upload').change(function(){
		var files,
		file;

		if (support.datauri) {
			$('#cover-view, #btn-bar').show();
			files = $(this).prop('files');

			if (files.length > 0) {
				file = files[0];

				if (file.type) {
					isImageFile = /^image\/\w+$/.test(file.type);
				} else {
					isImageFile = /\.(jpg|jpeg|png|gif)$/.test(file);
				}

				if (isImageFile) {
					if (this.url) {
						URL.revokeObjectURL(this.url); // Revoke the old one
					}
					this.url = URL.createObjectURL(file);

					$('#cover-preview').empty();

					$cover.cropper({
						aspectRatio: 7 / 12,
						autoCropArea: 1,
						minCropBoxWidth: 700,
						minCropBoxHeight: 1200,
						strict: false,
						guides: false,
						highlight: true,
						dragCrop: false,
						cropBoxMovable: false,
						cropBoxResizable: false,
						touchDragZoom: false,
						mouseWheelZoom: false,
						doubleClickToggle: false
					});
					$cover.cropper('replace', this.url);
					coverIsActive = true;

				} else {
					alert(msgUploadFileError);
				}
			}
		} else {
			alert(msgBrowserIsLow);
		}
	});


	$('.cover-btns').click(function(){
		if (coverIsActive) {
			data = $(this).data();
			if (data.method) {
				$cover.cropper(data.method, data.option);
			}
		}
	});

	$('#btn-preview').click(function(){

		if (!coverIsActive){
			alert(msgUploadFileFirst);
			return false;
		}
		$('.step-1').hide();
		$('.step-2').show();

		// $('#cover-preview').html('<img src="' + $cover.attr('src') + '">');
		canvas = $cover.cropper('getCroppedCanvas', {width:700, height:1200});
		context = canvas.getContext('2d');

		// 遍历像素点，将透明色改为不透明
		// var imageData = context.getImageData(0, 0, 700, 1200);
		// var pixels = imageData.data;
		// for (var i = 0; i < pixels.length; i++) {
		// 	if (pixels[i*4+3]==0){
		// 		pixels[i*4] = 244;
		// 		pixels[i*4+1] = 244;
		// 		pixels[i*4+2] = 244;
		// 		pixels[i*4+3] = 255;
		// 	}
		// };
		// context.putImageData(imageData, 0, 0);

		var kr = new Image();
		kr.src = getPhonePic();
		context.drawImage(kr, 0, 0, 700, 1200);

		$('#cover-preview').html(canvas);
		$('#preview-data').val(canvas.toDataURL());
	})

	$('#back-step-1').click(function(){
		$('.step-1').show();
		$('.step-2').hide();
	})


	function setPhonePic() {
		$('#phone').val();
		$('.cropper-face').css('background-image', 'url(ip5.png)');
	}

	function getPhonePic() {
		c = $('.cropper-face').css('background-image');
		r = c.match(/url\((.*)\)$/);
		return r[1];
	}
	
		
	$('#submit-to-cart').click(function() {
		$.blockUI({
			message: '正在加入购物车，请稍后⋯⋯'
		});
		return true;
	});
});


</script>

<style type="text/css">
#cover-view{
	height: 1200px;
	width: 950px;
	margin: 20px 0 20px 0;
}
#cover-preview{
	margin: 20px 0 20px 0;
	background-color: #fff;
}
.cropper-face{
	background: url(<? bloginfo('template_url'); ?>/images/ip5.png) no-repeat center 100%;
	width: 700px;
	height: 1200px;
	opacity: 1;
}
.cropper-view-box{
	width: 700px;
	height: 1200px;
	outline: 0px;
}
#cover-preview{
	text-align: center;
}
.custom{
	margin: 10px;
}
.custom h4{
	display: inline;
}
</style>

<?
require 'footer.php' ?>