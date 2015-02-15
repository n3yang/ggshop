<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Unionpay Payment Gateway
 *
 * Provides an Unionpay Payment Gateway.
 *
 * 
 *   测试卡号
 *   平安银行借记卡: 6216261000000000018
 *   证件号: 341126197709218366
 *   手机号: 13552535506
 *   SMS: 111111
 *   密码: 123456
 *   姓名: 全渠道
 *
 * @class 		WC_Unionpay
 * @extends		WC_Payment_Gateway
 * @version		1.0
 */

class WC_Unionpay extends WC_Payment_Gateway {

    // var $current_currency;
    // var $multi_currency_enabled;
    // var $supported_currencies;
    // var $lib_path;
    var $charset;

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

        // WPML + Multi Currency related settings
        // $this->current_currency       = get_option('woocommerce_currency');
        // $this->multi_currency_enabled = in_array( 'woocommerce-multilingual/wpml-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && get_option( 'icl_enable_multi_currency' ) == 'yes';
        // $this->supported_currencies   = array( 'RMB', 'CNY' );

        $this->charset                =  strtolower( get_bloginfo( 'charset' ) );
        if( !in_array( $this->charset, array( 'gbk', 'utf-8') ) ) {
            $this->charset = 'utf-8';
        }

        // WooCommerce required settings
        $this->id                     = 'unionpay';
        $this->icon                   = apply_filters( 'woocommerce_unionpay_icon', get_bloginfo('template_url').'/images/unionpay-logo.png', __FILE__  );
        $this->has_fields             = false;
        $this->method_title           = '银联支付';
        $this->order_button_text      = '使用银联支付';
        $this->notify_url             = WC()->api_request_url( 'WC_Unionpay' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        $this->merchantID  = $this->get_option( 'merchantID' );
        $this->title       = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->debug       = $this->get_option( 'debug' );
        $this->currencyCode= $this->get_option( 'currencyCode' );
        $this->signCert    = $this->get_option( 'signCert' );
        $this->signCertPwd = $this->get_option( 'signCertPwd' );
        $this->verifyCert  = $this->get_option( 'verifyCert' );

        $this->testmod     = $this->get_option( 'testmod' )=='yes' ? true : false ;
        if ($this->testmod) {
            $this->frontTransUrl = 'https://101.231.204.80:5000/gateway/api/frontTransReq.do';
        } else {
            $this->frontTransUrl = 'https://gateway.95516.com/gateway/api/frontTransReq.do';
        }

        // Logs
        $this->debug = $this->get_option( 'debug' )== 'yes' ? true : false ;

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) ); // WC <= 1.6.6
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) ); // WC >= 2.0
        add_action( 'woocommerce_thankyou_unionpay', array( $this, 'thankyou_page' ) );
        add_action( 'woocommerce_receipt_unionpay', array( $this, 'receipt_page' ) );

        // Payment listener/API hook
        add_action( 'woocommerce_api_wc_unionpay', array( $this, 'check_unionpay_response' ) );

        // Display Unionpay Trade No. in the backend.
        add_action( 'woocommerce_admin_order_data_after_billing_address',array( $this, 'wc_unionpay_display_order_meta_for_admin' ) );
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
        <h3>银联支付平台</h3>
       
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
                'label'     => '启用银联支付',
                'default'   => 'no'
            ),
            'title' => array(
                'title'       => '银联支付',
                'type'        => 'text',
                'description' => '支付过程中所显示的支付名称',
                'default'     => '银联支付'
            ),
            'description'=> array(
                'title'         => '银联支付方式的描述',
                'type'          => 'textarea',
                'default'       => '使用支持银联的银行卡进行付款，其中包括借记卡及信用卡付款。',
                'description'   => '输入一段文字来介绍银联支付'
            ),
            'merchantID' => array(
                'title'       => '商户编号',
                'type'        => 'text',
                'description' => '',
                'css'         => 'width:400px'
            ),
            'currencyCode' => array(
                'title'         => '交易币种',
                'type'          => 'text',
                'description'   => '默认156（人民币）',
                'default'       => '156',
            ),
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
                'description'   => '验证签名公钥文件绝对路径。在SDK中，测试环境为verify_sign_acp.cert，正式环境为UpopRsaCert.cert',
            ),
            'testmod' => array(
                'title'         => '测试模式',
                'type'          => 'checkbox',
                'label'         => '启用',
                'description'   => '是否启用测试模式。如果启用，在支付时跳转的地址将为银联的测试地址：https://101.231.204.80:5000/gateway/api/frontTransReq.do 。 否则将跳转到正式地址：https://gateway.95516.com/gateway/api/frontTransReq.do',
            ),
            'debug' => array(
                'title'       => 'debug模式',
                'type'        => 'checkbox',
                'label'       => '启用log',
                'default'     => 'yes',
                'description' => '是否启用LOG。日志存储目录：woocommerce/logs/unionpay',
            )
        );
    }

    /**
     * Return page of unionpay, show unionpay Trade No. 
     *
     * @access public
     * @param mixed Sync Notification
     * @return void
     */
    function thankyou_page( $orderId ) {

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
            update_post_meta( $orderId, 'Unionpay Trade No.', wc_clean( $_POST['queryId'] ) );
            $this->log('Unionpay Trade No. '.$_POST['queryId'], 'Success');
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
        echo $this->build_gateway_form( $orderId );
    }

    function build_gateway_form($orderId) {

        $order = new WC_Order($orderId);

        // 获取签名证书ID
        $pkcs12certdata = file_get_contents ( $this->signCert );
        openssl_pkcs12_read ( $pkcs12certdata, $certs, $this->signCertPwd );
        $x509data = $certs ['cert'];
        openssl_x509_read ( $x509data );
        $certdata = openssl_x509_parse ( $x509data );
        $certId = $certdata ['serialNumber'];

        // 获取签名证书私钥
        $signCertPrivateKey = $certs ['pkey'];

        $params = array(
            'version'           => '5.0.0',                         //版本号
            'encoding'          => 'UTF-8',                         //编码方式
            'certId'            => $certId,                         //证书ID
            'txnType'           => '01',                            //交易类型  
            'txnSubType'        => '01',                            //交易子类
            'bizType'           => '000000',                        //业务类型
            'frontUrl'          =>  $this->get_return_url($order),  //前台通知地址
            'backUrl'           =>  $this->notify_url,              //后台通知地址    
            'signMethod'        => '01',                            //签名方法
            'channelType'       => '07',                            //渠道类型
            'accessType'        => '0',                             //接入类型
            'merId'             => $this->merchantID,               //商户代码
            'orderId'           => date('YmdHis').$orderId,         //商户订单号
            'txnTime'           => date('YmdHis'),                  //订单发送时间
            'txnAmt'            => $order->get_total() * 100,       //交易金额，单位为分
            'currencyCode'      => '156',                           //交易币种
            'defaultPayType'    => '0001',                          //默认支付方式    
        );
        $params_str = $this->build_params_str($params);

        $params_sha1x16 = sha1 ( $params_str, FALSE );

        // 签名
        $signFlag = openssl_sign ( $params_sha1x16, $signature, $signCertPrivateKey, OPENSSL_ALGO_SHA1 );
        if ($signFlag) {
            $signature_base64 = base64_encode ( $signature );
            $params ['signature'] = $signature_base64;
        } else {
            $this->log( 'signature faild' );
        }

        foreach ($params as $k => $v) {
            $form.="<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n" ;
        }
        $p = '<html>'
            .'<body onload="form_pay.submit()">'
            .'<form id="form_pay" name="form_pay" method="post" action="'.$this->frontTransUrl.'">'
            .$form
            .'</form>'
            .'</body>'
            .'</html>';
        exit($p);
    }


    /**
     * Check for unionpay IPN Response
     *
     * @access public
     * @return void
     */
    function check_unionpay_response() {

        if (empty($_POST)) {
            wp_die("Invalid Requirements");
        }

        if (!$this->verify_response($_POST)){
            $this->log('Unionpay response verify faild! post:'.json_encode($_POST));
            wp_die("Invalid Requirements");
        }

        $orderId = substr($_POST['orderId'], 14, strlen($_POST['orderId']));
        $order = new WC_Order($orderId);
        if( $order->status != 'completed'){
            $order->payment_complete();
            $order->add_order_note ('支付成功');
            update_post_meta( $orderId, 'Unionpay Trade No.', wc_clean( $_POST['queryId'] ) );
            $this->log('Payment Completed! Order ID: ' . $orderId . 'date:' . json_encode($_POST), 'Info');
            header( 'HTTP/1.1 200 OK' );
            echo "Success";
            exit;
        }

    }

    /**
     * Complete order when customer release funds from Unionpay
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
     * Sanitize user input
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
        $this->logger->add('unionpay', $message);
        return true;
    }

    /**
     * 将数组转换为 key=value 的形式并使用 & 连接
     * @param  array $params 
     * @return string         
     */
    function build_params_str($params)
    {
        ksort($params);
        foreach ($params as $key => $value) {
            if ($key == 'signature') {
                continue;
            }
            $params_str .= sprintf("%s=%s&", $key, $value);
        }
        return substr($params_str, 0, strlen($params_str)-1);
    }

    /**
     * Display unionpay Trade No. in the backend.
     * 
     * @access public
     * @param mixed $order
     * @since 1.3
     * @return void
     */
    function wc_unionpay_display_order_meta_for_admin( $order ){
        $trade_no = get_post_meta( $order->id, 'unionpay Trade No.', true );
        if( !empty($trade_no ) ){
            echo '<p><strong>银联交易流水号：</strong><br />' .$trade_no. '</p>';
        }
    }
}
