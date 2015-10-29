<?php



/**
* 
*/
class Wc_Kuaidi100_Tracking_Sync
{
	public $debug = true;
	public $logger = '';
	public static $cname_mapping = array(
		'yunda' => '韵达快递'
	);

	function __construct()
	{
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}

		add_action( 'woocommerce_api_kuaidi100_sync', array( $this, 'wc_api_kuaidi100_callback' ) );
	}

	/**
	 * Adds the meta box container
	 * @param string $post_type 
	 */
	public function add_meta_box($post_type)
	{
		add_meta_box( 
			'wc-kuaidi100-track', 
			'快递信息', 
			array( $this, 'render_meta_box' ), 
			'shop_order', 
			'normal',
			'default' 
		);
	}

	/**
	 * display meta box
	 * @param  object $post 
	 */
	public function render_meta_box($post)
	{
		wp_nonce_field( 'wc_kuaidi100_save', 'kuaidi100_save_nonce' );
		$track_info = get_post_meta($post->ID, '_kuaidi100_track_info', true);

		echo '
			<select name="kuaidi100_company">
				<option value="yunda" selected="selected">韵达快递</option>
			</select>
			<input type="text" name="kuaidi100_track_id" value="'.$track_info['id'].'" placeholder="请输入快递单号">
		';

		// display tracking log
		$track_log = json_decode(get_post_meta($post->ID, '_kuaidi100_track_log', true), true);
		$track_log_data = empty($track_log['lastResult']['data']) ? array() : $track_log['lastResult']['data'];
		foreach ($track_log_data as $v) {
			$log .= sanitize_text_field($v['ftime']) . "\t" . sanitize_text_field($v['context']) . "\n";
		}
		if ( !empty($log) ) {
			echo '<hr /><pre style="overflow:scroll">' . $log . '</pre>';
		}
	}

	/**
	 * Save the meta when the post is saved.
	 * @param  int $post_id 
	 */
	public function save($post_id)
	{
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['kuaidi100_save_nonce'], 'wc_kuaidi100_save' ) ) {
			return $post_id;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'shop_order' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_posts', $post_id ) ) {
				return $post_id;
			}
		}
		// save data
		if ( ! isset( $_POST['kuaidi100_track_id'] ) ) {
			return $post_id;
		}

		// If we got new data, lets subscribe it
		$old_track_info = get_post_meta( $post_id, '_kuaidi100_track_info', true);
		if ( isset($old_track_info['salt']) ) {
			unset($old_track_info['salt']);
		}
		$new_track_info = array(
			'company' => sanitize_text_field( $_POST['kuaidi100_company'] ),
			'id'      => sanitize_text_field( $_POST['kuaidi100_track_id'] ),
		);
		@ksort($new_track_info);
		@ksort($old_track_info);
		// compare the data
		if ( md5(json_encode($new_track_info)) != md5(json_encode($old_track_info)) ) {
			
			$subscribe = array();
			$salt = substr(md5(time()), 0, 8);
			update_post_meta( $post_id, '_kuaidi100_track_info', $new_track_info);

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
			$this->log('Subsribe: ' . json_encode($new_track_info), 'Info');

			// TODO: 异步订阅提升可靠性（WP CRON）
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
			$this->log('Subscribe result: ' . json_encode($result), 'Info');
			// Update the meta field in the database.
			$new_track_info['salt'] = $salt;
			update_post_meta( $post_id, '_kuaidi100_track_info', $new_track_info );
			$order = new WC_Order($post_id);
			$order->add_order_note("更新快递信息: {$new_track_info['company']} {$new_track_info['id']}");
		}

	}

	/**
	 * update tracking log when kuaidi100 callback.
	 * 
	 * example tracking number:1000556304735
	 */
	public function wc_api_kuaidi100_callback()
	{
		// $this->log(var_export($_REQUEST, 1));

		$post_id = intval($_GET['p']);
		if ( ! $post_id ) {
			$this->log('Failed. Need param p');
			exit;
		}

		// check salt value
		$info = get_post_meta($post_id, '_kuaidi100_track_info', true);
		$salt = empty($info['salt']) ? '' : $info['salt'];
		if ( ! $salt ) {
			exit;
		}
		if ( md5(stripcslashes($_POST['param']).$salt) != strtolower($_POST['sign']) ) {
			$error = 'Cheking fails. My md5:'.md5($_POST['param'].$salt) . ', ' . 'post md5:'.$_POST['sign'];
			$error .= 'Input Data: ' . var_export($_REQUEST, 1);
			$this->log($error);
			exit;
		}

		$update_result = update_post_meta( $post_id, '_kuaidi100_track_log', $_POST['param'] );
		$this->log('Receive API request: q=' . $_REQUEST['q'] . ' update: ' . var_export($update_result, 1), 'Success');
		// print result
		$rdata = array(
			'result'     => true,
			'returnCode' => 200,
			'message'    => '成功'
		);
		echo json_encode($rdata);
		exit;
	}


	/**
	 * write log
	 * @param  string $message the message
	 * @param  string $type    message type
	 * @return bool            true
	 */
	function log($message, $type='Error'){
		if ( strtolower($type) == 'info' && ! $this->debug ) {
			return;
		}
		if ( ! $this->logger ) {
			$this->logger = new WC_Logger();
		}
		$message = '['.$type.'] ' . $message;
		$this->logger->add('kuaidi100', $message);
		return true;
	}
}

new Wc_Kuaidi100_Tracking_Sync();


function ggshop_get_kuaidi100_company_name($post_id)
{
	$info = get_post_meta($post_id, '_kuaidi100_track_info', true);
	return Wc_Kuaidi100_Tracking_Sync::$cname_mapping[$info['company']];
}
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
