<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Wechatpay Payment Gateway
 *
 * Wechatpay Payment Gateway for Woocommerce
 *
 * @class 		WC_Wechatpay
 * @extends		WC_Payment_Gateway
 * @version		1.0
 */

class WC_Wechatpay extends WC_Payment_Gateway {

    var $currencyCode = '';
    // 签名证书绝对路径
    var $signCert = '';
    var $signCertPwd = '';
    // 验证签名公钥文件绝对路径
    var $verifyCert = '';
    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        // WooCommerce required settings
        $this->id                     = 'wechatpay';
        $this->icon                   = apply_filters( 'woocommerce_wechatpay_icon', get_bloginfo('template_url').'/images/wechatpay-logo.png', __FILE__  );
        $this->has_fields             = false;
        $this->method_title           = '微信支付';
        $this->order_button_text      = '使用微信支付';
        $this->notify_url             = WC()->api_request_url( 'WC_Wechatpay' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->appId       = $this->get_option( 'appId' );
        $this->merchantId  = $this->get_option( 'merchantId' );
        $this->merchantKey = $this->get_option( 'merchantKey' );
        $this->qrcodeSize  = $this->get_option( 'qrcodeSize' );
        $this->debug       = $this->get_option( 'debug' );

        // Logs
        $this->debug = $this->get_option( 'debug' )== 'yes' ? true : false ;

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) ); // WC <= 1.6.6
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) ); // WC >= 2.0
        add_action( 'woocommerce_thankyou_wechatpay', array( $this, 'thankyou_page' ) );
        add_action( 'woocommerce_receipt_wechatpay', array( $this, 'receipt_page' ) );

        // Payment listener/API hook
        add_action( 'woocommerce_api_wc_wechatpay', array( $this, 'check_wechatpay_response' ) );

        // Display Wechatpay Trade No. in the backend.
        add_action( 'woocommerce_admin_order_data_after_billing_address',array( $this, 'wc_wechatpay_display_order_meta_for_admin' ) );
    }



    /**
     * Admin Panel Options
     * - Options for bits like 'title' and account etc.
     *
     * @access public
     * @return void
     */
    public function admin_options() {

        ?>
        <h3>微信支付平台</h3>
       
        <table class="form-table">
            <?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
            ?>
        </table><!--/.form-table-->
        <?php
    }
    
    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title'     => '启用',
                'type'      => 'checkbox',
                'label'     => '启用微信支付',
                'default'   => 'no'
            ),
            'title' => array(
                'title'       => '微信支付',
                'type'        => 'text',
                'description' => '支付过程中所显示的支付名称',
                'default'     => '微信支付'
            ),
            'description'=> array(
                'title'         => '微信支付方式的描述',
                'type'          => 'textarea',
                'default'       => '使用微信绑定的银行卡进行付款，扫一扫即可完成支付。',
                'description'   => '输入一段文字来介绍微信支付'
            ),
            'appId' => array(
                'title'       => 'APP ID',
                'type'        => 'text',
                'description' => '公众号身份的唯一标识，绑定支付的APPID（必须配置）。在微信发送的邮件中查看。',
                'css'         => 'width:400px'
            ),
            'merchantId' => array(
                'title'       => '商户号',
                'type'        => 'text',
                'description' => '商户号（必须配置）。在财付通发送的邮件中查看。',
                'css'         => 'width:400px'
            ),
            'merchantKey' => array(
                'title'       => '商户支付密钥',
                'type'        => 'text',
                'description' => '商户支付密钥，32位，参考开户邮件设置（必须配置）。',
                'css'         => 'width:400px'
            ),
            // 预留证书配置
            /*
            'signCert' => array(
                'title'         => '签名证书',
                'type'          => 'text',
                'description'   => '签名证书文件所在目录的绝对路径',
            ),
            'signCertPwd' => array(
                'title'         => '签名证书密码',
                'type'          => 'password',
                'description'   => '签名证书密码',
            ),
            'verifyCert' => array(
                'title'         => '验证签名公钥文件',
                'type'          => 'text',
                'description'   => '验证签名公钥文件绝对路径。',
            ),
            */
            'qrcodeSize' => array(
                'title'       => '生成图片二维码的大小',
                'default'     => '8',
                'type'        => 'text',
                'description' => '数字越大，图片越大，默认为8',
                'css'         => 'width:400px'
            ),
            'debug' => array(
                'title'       => 'debug模式',
                'type'        => 'checkbox',
                'label'       => '启用详细日志记录',
                'default'     => 'yes',
                'description' => '将记录更详细的日志信息，否则将仅记录关键日志。日志存储目录：woocommerce/logs/wechatpay',
            )
        );
    }

    /**
     * Return page of wechatpay, show wechatpay Trade No. 
     *
     * @access public
     * @param mixed Sync Notification
     * @return void
     */
    function thankyou_page( $orderId ) {

        return;

        $_POST = stripslashes_deep( $_POST );
        $this->log('received front notify post: '.json_encode($_POST), 'Info');
        if (!$this->verify_response($_POST)){
            $this->log('verify response faild');
            return false;
        }
        
        $notifyOrderId = substr($_POST['orderId'], 14, strlen($_POST['orderId']));
        if ($orderId!=$notifyOrderId){
            echo "<p><strong>订单号错误！</strong></p>";
            $this->log('error order ID');
            return false;
        }

        if ($_POST['respCode']!='00'){
            echo "<p>订单支付失败！请重新支付</p>";
            $this->log('error payment result. respCode:'.$_POST['respCode']);
            return false;
        }

        $order = new WC_Order($orderId);

        if( $order->status != 'completed'){
            $order->payment_complete();
            $order->add_order_note ('支付成功');
            update_post_meta( $orderId, 'Wechatpay Trade No.', wc_clean( $_POST['queryId'] ) );
            $this->log('Wechatpay Trade No. '.$_POST['queryId'], 'Success');
            return true;
        }

    }

    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    function process_payment( $order_id ) {

        $order = new WC_Order( $order_id );

        return array(
                'result'   => 'success',
                'redirect' => $order->get_checkout_payment_url( true ),
            );
    }

    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
    function receipt_page( $orderId ) {
        echo '';
        echo $this->build_qrcode_image( $orderId );
        echo '';
    }

    /**
     * 构建微信支付扫描的二维码
     * @param  int     $orderId 订单号
     * @return string           二维码图片的html标签
     */
    function build_qrcode_image($orderId) {

        $nativeDs["appid"] = $this->appId;
        $nativeDs['mch_id'] = $this->merchantId;
        $nativeDs["product_id"] = $orderId;
        $nativeDs["time_stamp"] = time();
        $nativeDs["nonce_str"] = $this->create_noncestr();
        // build signature string
        $signStr = $this->build_params_str($nativeDs).'&key='.$this->merchantKey;
        $nativeDs["sign"] = strtoupper(md5($signStr));
        // build pay url string
        $bizstr = $this->build_params_str($nativeDs, false);
        echo $bizurl = "weixin://wxpay/bizpayurl?".$bizstr;
        // build QR code
        require_once 'phpqrcode.class.php';
        ob_start();
        $errorCorrectionLevel = "L";
        QRcode::png($bizurl, false, $errorCorrectionLevel, 5, 2);
        $imageString = base64_encode( ob_get_contents() );
        ob_end_clean();
        return '<img src="data:image/png;base64,'.$imageString.'" />';
    }


    /**
     * Check for wechatpay IPN Response
     *
     * @access public
     * @return void
     */
    function check_wechatpay_response() {

        if (empty($_POST)) {
            wp_die("Invalid Requirements");
        }

        file_put_contents('/tmp/wxpay.log', json_encode($_POST));
        exit;

        if (!$this->verify_response($_POST)){
            $this->log('Wechatpay response verify faild! post:'.json_encode($_POST));
            wp_die("Invalid Requirements");
        }

        $orderId = substr($_POST['orderId'], 14, strlen($_POST['orderId']));
        $order = new WC_Order($orderId);
        if( $order->status != 'completed'){
            $order->payment_complete();
            $order->add_order_note ('支付成功');
            update_post_meta( $orderId, 'Wechatpay Trade No.', wc_clean( $_POST['queryId'] ) );
            $this->log('Payment Completed! Order ID: ' . $orderId . 'date:' . json_encode($_POST), 'Info');
            header( 'HTTP/1.1 200 OK' );
            echo "Success";
            exit;
        }

    }

    /**
     * Complete order when customer release funds from Wechatpay
     *
     * By default woocommerce doesn't complete order automatically if order status is processing.
     * So we have to deal with this process, order is supposed to be completed when customer release funds.
     *
     * @param mixed $order
     * @since 1.3
     * @return void
     */
    function payment_complete( $order ){

        if( $order->status == 'processing' ){

            $order->update_status( 'completed' );

            add_post_meta( $order->id, '_paid_date', current_time('mysql'), true );

            $this_order = array(
                'ID' => $order->id,
                'post_date' => current_time( 'mysql', 0 ),
                'post_date_gmt' => current_time( 'mysql', 1 )
            );
            wp_update_post( $this_order );
            
            if ( apply_filters( 'woocommerce_payment_complete_reduce_order_stock', true, $order->id ) ) {
                $order->reduce_order_stock(); // Payment is complete so reduce stock levels
            }

            do_action( 'woocommerce_payment_complete', $order->id );
        }
    }

    /**
     * 验证支付平台通知消息
     *
     * @access public
     * @param string $str
     * @since 1.3
     * @return string
     */
    function verify_response($p) {
        // 公钥
        if (!$this->verifyCert) {
            $this->log('verify response...Can not find verify Cert file.');
            return false;
        }
        $x509data = file_get_contents ( $this->verifyCert );
        openssl_x509_read ( $x509data );
        $certdata = openssl_x509_parse ( $x509data );
        $fileCertId = $certdata ['serialNumber'];
        if ($fileCertId!=$p['certId']) {
            $this->log('verify response... cert ID faild.');
            return false;
        }
        $public_key = $x509data;
        $this->log('verify response...: cert id: '.$p['certId'], 'Info');

        // 签名串
        $signature_str = $p['signature'];
        unset ( $p['signature'] );
        $params_str = $this->build_params_str($p);
        $this->log('verify response...params str:' . $params_str, 'Info');
        $signature = base64_decode ( $signature_str );
        $params_sha1x16 = sha1 ( $params_str, FALSE );
        $isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
        $this->log( $isSuccess ? 'Success: verify response...' : 'Error: verify response...', 'Info' );
        return $isSuccess;
    }

    /**
     * write log
     * @param  string $message the message
     * @param  string $type    message type
     * @return bool            true or false
     */
    function log($message, $type='Error'){
        if ( $this->debug!='yes' ) {
            return;
        }
        if (!$this->logger) {
            $this->logger = new WC_Logger();
        }
        $message = '['.$type.'] '.$message;
        $this->logger->add('wechatpay', $message);
        return true;
    }

    /**
     * 将数组转换为 key=value 的形式并使用 & 连接，如果passSign=true，将跳过sign参数
     * 
     * @param  array $params 
     * @param  bool $passSign
     * @return string         
     */
    function build_params_str($params, $passSign=false)
    {
        ksort($params);
        foreach ($params as $key => $value) {
            if ($key == 'sign' && $passSign) {
                continue;
            }
            $params_str .= sprintf("%s=%s&", $key, $value);
        }
        return substr($params_str, 0, strlen($params_str)-1);
    }
    /**
     * 产生一个随机只包含字母大小写及数字的字符串
     * @param  integer $length 
     * @return string
     */
    function create_noncestr( $length = 32 ) {  
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }

    /**
     * Display wechatpay Trade No. in the backend.
     * 
     * @access public
     * @param mixed $order
     * @since 1.3
     * @return void
     */
    function wc_wechatpay_display_order_meta_for_admin( $order ){
        $trade_no = get_post_meta( $order->id, 'wechatpay Trade No.', true );
        if( !empty($trade_no ) ){
            echo '<p><strong>微信交易流水号：</strong><br />' .$trade_no. '</p>';
        }
    }
}
