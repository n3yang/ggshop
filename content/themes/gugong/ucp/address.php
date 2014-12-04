<?php

ggshop_redirect_not_login();

if ($_POST['submit']){
	update_user_meta( get_current_user_id(), 'shipping_first_name', sanitize_text_field($_POST['realname']) );
	update_user_meta( get_current_user_id(), 'shipping_last_name', sanitize_text_field($_POST['phone']) );
	update_user_meta( get_current_user_id(), 'shipping_address_1', sanitize_text_field($_POST['address']) );
	$message = '已经成功保存新的收货地址';
}

$checkout = WC()->checkout;

get_header();
?>

	<div class="main user_center_bg">

		<div class="user_center_wrap base-clear">
			
			<?php get_template_part('ucp/ucp-menu') ?>
			<div class="user_center_box">
				
				<div class="user_center_title">
					<h3>收货地址</h3>
					<div class="user_center_cen">
						<form method="post">
						<?php
						if ($message){
							echo '<div style="padding-left:130px">'.$message.'</div>';
						}
						?>
						<table class="form_table" width="100%" align="center" border="0" cellpadding="5" cellspacing="1">
							<tbody>
								<tr>
									<td width="100">
										收货人姓名：
									</td>
									<td>
										<input id="realname" name="realname" class="form_text" type="text" value="<?php echo $checkout->get_value('shipping_first_name');?>">
									</td>
								</tr>
								<tr>
									<td>
										手机号码：
									</td>
									<td>
										<input id="phone" name="phone" class="form_text" type="text" value="<?php echo $checkout->get_value('shipping_last_name');?>">
									</td>
								</tr>
								<tr>
									<td>
										省市区：
									</td>
									<td class="shop_add_box">
							            <select class="select" name="province" id="s1">
							              <option></option>
							            </select>
							            <select class="select" name="city" id="s2">
							              <option></option>
							            </select>
							            <select class="select" name="town" id="s3">
							              <option></option>
							            </select>
									</td>
								</tr>
								<tr>
									<td>
										详细地址：
									</td>
									<td>
										<input id="address" name="address" class="form_text" type="text" style="width:400px" value="<?php echo $checkout->get_value('shipping_address_1');?>">
									</td>
								</tr>
								<tr>
									<td>
										
									</td>
									<td>
										<input class="form_btn" type="submit" name="submit" value="保存新的收货地址" />
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
				</div>


			</div>

		</div>


	</div>

<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/geo.js"></script>
<script>
	
function promptinfo()
{
    var s1 = document.getElementById('s1');
    var s2 = document.getElementById('s2');
    var s3 = document.getElementById('s3');
}

$(function () {
	setup();
	preselect('<?php echo $checkout->get_value('shipping_address_1');?>');
	promptinfo();

	$(".shop_add_box select").change(function () {
		// $(".shop_add_box label").css({
		// 	"border": "2px solid #fff"
		// })
		// $(this).siblings('label').css({
		// 	"border": "2px solid #3db79b"
		// })
		var address='';
		$('#s1, #s2, #s3').each(function(i) {
			selected = $(this).find('option:selected');
			if (selected.index()!=0) {
				if (i==1 && $('#s1 option:selected').index()<5){
					return;
				}
				address+=selected.val();
			}
		});;
		$('#address').val(address);
	})


})
</script>
<?php get_footer() ?>