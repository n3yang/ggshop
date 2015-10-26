<?php



/**
 * 
 */
// 增加自定义后台显示
add_action( 'add_meta_boxes', function (){
	add_meta_box( 
		'wc-kuaidi100-track', 
		'快递信息', 
		'wc_kuaidi100_callback', 
		'shop_order', 
		'side', 
		'high' 
	);
});

function wc_kuaidi100_callback()
{
	wp_nonce_field( 'wc_kuaidi100_save', 'kuaidi100_save_nonce' );
	
	echo '
		<select name="kuaidi100_company">
			<option value="yunda" selected="selected">韵达快递</option>
		</select>
		<input type="text" name="kuaidi100_track_id" value="" placeholder="请输入快递单号">
	';
}

function wc_kuaidi100_save($post_id)
{
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['kuaidi100_save_nonce'], 'wc_kuaidi100_save' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'shop_order' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			return;
		}
	}
	// save data
	if ( ! isset( $_POST['kuaidi100_track_id'] ) ) {
		return;
	}

	// if the first time, lets subscribe
	$old_track_id = get_post_meta( $post_id, '_kuaidi100_track_id', true);
	$old_company = get_post_meta( $post_id, '_kuaidi100_company', true);
	if ( $old_track_id != $_POST['kuaidi100_track_id'] ||  $old_company != $_POST['kuaidi100_company'] ) {
		$subscribe = array();
		$salt = substr(md5(time()), 0, 8);

		$subscribe["schema"] = 'json' ;
		$subscribe["param"] = json_encode(array(
			'company'		=> $_POST['kuaidi100_company'],
			'number'		=> $_POST['kuaidi100_track_id'],
			'key'			=> 'gpMFwvVj273',
			'parameters'	=> array(
				'callbackurl' => WC()->api_request_url( 'kuaidi100_sync' ) . '?p=' . $post_id,
				'salt' => $salt
			)
		));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, 'http://www.kuaidi100.com/poll');
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($subscribe));
		$result = curl_exec($ch);
		$result = json_decode($result);
		// success or repoll
		if ( $result['returnCode']=='200' || $result['returnCode']=='501' ) {
			
		} else {

		}
		// Update the meta field in the database.
		update_post_meta( $post_id, '_kuaidi100_company', sanitize_text_field( $_POST['kuaidi100_company'] ) );
		update_post_meta( $post_id, '_kuaidi100_track_id', sanitize_text_field( $_POST['kuaidi100_track_id'] ) );
		update_post_meta( $post_id, '_kuaidi100_poll_salt', $salt);
	}
}
add_action( 'save_post', 'wc_kuaidi100_save' );


// kuaidi100 callback
// 1000556304735
add_action( 'woocommerce_api_kuaidi100_sync', function(){
	
	error_log(var_export($_REQUEST, 1), 3, '/tmp/kd.log');
	$post_id = intval($_GET['p']);
	if ( !$post_id ) {
		return;
	}

	// check salt value
	$salt = get_post_meta($post_id, '_kuaidi100_poll_salt', true);
	if ( !$salt ) {
		return;
	}
	if ( md5($_POST['param'].$salt) != $_POST['sign'] ) {
		return;
	}

	update_post_meta( $post_id, '_kuaidi100_track_result', $_POST['param'] );

	die;
});


/*
// handle REST/legacy API request
add_action( 'parse_request', 'fhandle_api_requests', 0 );
function fhandle_api_requests() {
	// echo $_GET['user'];
	var_dump($_SERVER['REQUEST_URI']);
	exit;
}
*/
// unset( WC()->session->order_awaiting_payment )




/**************/
// add_filter('woocommerce_api_classes', function(){

// });

// woocommerce_api_check_authentication
