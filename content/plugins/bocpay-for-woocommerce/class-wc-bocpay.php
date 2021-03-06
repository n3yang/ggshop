<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Bocpay Payment Gateway
 *
 * Provides an Bocpay Payment Gateway.
 *
 * @class 		WC_Bocpay
 * @extends		WC_Payment_Gateway
 * @version		1.0
 */

class WC_Bocpay extends WC_Payment_Gateway {

    var $current_currency;
    // var $multi_currency_enabled;
    var $supported_currencies;
    // var $lib_path;
    var $charset;
    var $sock_url = 'tcp://127.0.0.1:8891';

    /**
     * Constructor for the gateway.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        // WPML + Multi Currency related settings
        $this->current_currency       = get_option('woocommerce_currency');
        // $this->multi_currency_enabled = in_array( 'woocommerce-multilingual/wpml-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && get_option( 'icl_enable_multi_currency' ) == 'yes';
        $this->supported_currencies   = array( 'RMB', 'CNY' );

        $this->charset                =  strtolower( get_bloginfo( 'charset' ) );
        if( !in_array( $this->charset, array( 'gbk', 'utf-8') ) ) {
            $this->charset = 'utf-8';
        }

        // WooCommerce required settings
        $this->id                     = 'bocpay';
        $this->icon                   = apply_filters( 'woocommerce_bocpay_icon', plugins_url( 'images/boc-logo.png', __FILE__ ) );
        $this->has_fields             = false;
        $this->method_title           = '交通银行支付';
        $this->order_button_text      = '使用交通银行支付';
        $this->notify_url             = WC()->api_request_url( 'WC_Bocpay' );

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        $this->merchantID  = $this->get_option( 'merchantID' );
        $this->title       = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->debug       = $this->get_option( 'debug' );

        // Logs
        $this->debug = 'yes';
        if ( 'yes' == $this->debug ) {
            $this->log = new WC_Logger();
        }

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) ); // WC <= 1.6.6
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) ); // WC >= 2.0
        add_action( 'woocommerce_thankyou_bocpay', array( $this, 'thankyou_page' ) );
        add_action( 'woocommerce_receipt_bocpay', array( $this, 'receipt_page' ) );

        // Payment listener/API hook
        add_action( 'woocommerce_api_wc_bocpay', array( $this, 'check_bocpay_response' ) );

        // Display Bocpay Trade No. in the backend.
        add_action( 'woocommerce_admin_order_data_after_billing_address',array( $this, 'wc_bocpay_display_order_meta_for_admin' ) );
    }

    /**
     * Check if this gateway is enabled and available for the selected main currency
     *
     * @access public
     * @return bool
     */
    function is_available() {

        $is_available = ( 'yes' === $this->enabled ) ? true : false;

        if ($this->multi_currency_enabled) {
            if ( !in_array( get_woocommerce_currency(), array( 'RMB', 'CNY') ) && !$this->exchange_rate) {
                $is_available = false;
            }
        } else if ( !in_array( $this->current_currency, array( 'RMB', 'CNY') ) && !$this->exchange_rate) {
            $is_available = false;
        }

        return $is_available;
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
        <h3>交通银行支付平台</h3>
       
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
                'label'     => '启用交通银行支付',
                'default'   => 'no'
            ),
            'title' => array(
                'title'       => '',
                'type'        => 'text',
                'description' => '支付过程中所显示的支付名称',
                'default'     => '交通银行支付'
            ),
            'description'   => array(
                'title'     => '交通银行',
                'type'      => 'textarea',
                'default'   => '',
            ),
            'merchantID' => array(
                'title'       => '商户编号',
                'type'        => 'text',
                'description' => '',
                'css'         => 'width:400px'
            ),
            'debug' => array(
                'title'       => 'debug模式',
                'type'        => 'checkbox',
                'label'       => '启用log',
                'default'     => 'no',
                'description' => '选项无效，LOG始终启用，woocommerce/logs/bocpay',
            )
        );
    }

    /**
     * Return page of Bocpay, show Bocpay Trade No. 
     *
     * @access public
     * @param mixed Sync Notification
     * @return void
     */
    function thankyou_page( $order_id ) {

        $_POST = stripslashes_deep( $_POST );

        if (empty($_POST['notifyMsg'])) {
            // $this->log->add('bocpay', '');
            return;
        }

        $notifyMsg = $_POST['notifyMsg'];

        // verify the payment result from boc
        $fp = stream_socket_client($this->sock_url, $errno, $errstr, 30);
        if (!$fp) {
            $this->log->add('bocpay', "code: 999999\tmessage:".$notifyMsg);
            echo ("支付网管错误，请稍后再试");
            return;
        }

        $in  = "<?xml version='1.0' encoding='UTF-8'?>";
        $in .= "<Message>";
        $in .= "<TranCode>cb2200_verify</TranCode>";
        $in .= "<merchantID>".$this->merchantID."</merchantID>";
        $in .= "<MsgContent>".$notifyMsg."</MsgContent>";
        $in .= "</Message>";
        fwrite($fp, $in);
        while (!feof($fp)) {
            $retMsg =$retMsg.fgets($fp, 1024);
        }
        fclose($fp);

        $dom = new DOMDocument;
        $dom->loadXML($retMsg);

        $retCode = $dom->getElementsByTagName('retCode');
        $retCode_value = $retCode->item(0)->nodeValue;
        
        $errMsg = $dom->getElementsByTagName('errMsg');
        $errMsg_value = $errMsg->item(0)->nodeValue;

        if ($retCode_value != '0'){
            $this->log->add('bocpay', "code: $retCode_value\tmessage: $notifyMsg");
            echo ("支付网关错误，请稍后再试");
            return;
        }
        
        $sources = explode('|', $notifyMsg);
        if ($order_id!=$sources[5]){
            echo "<p><strong>订单号错误！</strong></p>";
            return;
        }

        if ($sources[9]!='1'){
            echo "<p>订单支付失败！请重新支付</p>";
            return;
        }

        $order = new WC_Order($order_id);

        if( $order->status != 'completed'){
            $order->payment_complete();
            $order->add_order_note ('支付成功');
            update_post_meta( $order_id, 'Bocpay Trade No.', wc_clean( $sources[8] ) );
            $this->log->add('bocpay', "code: 000000\tmessage: ".$notifyMsg);
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

//woocommerce_payment_successful_result
        // do bocpay socket

        $sources['interfaceVersion'] = '1.0.0.0'; // 消息版本号
        $sources['merID'] = $this->merchantID; // 商户号
        $sources['orderid'] = date('Ymd').date('His').$order->id; // 订单号
        $sources['orderDate'] = date('Ymd'); // 商户订单日期
        $sources['orderTime'] = date('His'); // 商户订单时间
        $sources['tranType'] = '0'; // 交易类别 0:B2C
        $sources['amount'] = $order->get_total(); // 订单金额
        $sources['curType'] = 'CNY'; // 交易币种
        $sources['orderContent'] = '';// 订单内容
        $sources['orderMono'] = '';// 商家备注
        $sources['phdFlag'] = ''; // 物流配送标志
        $sources['notifyType'] = '1'; // 通知方式
        $sources['merURL'] = $this->notify_url; // 主动通知URL
        $sources['goodsURL'] = $this->get_return_url( $order ); // 取货URL
        $sources['jumpSeconds'] = ''; // 自动跳转时间
        $sources['payBatchNo'] = $order->id; // 商户批次号
        $sources['proxyMerName'] = ''; // 代理商家名称
        $sources['proxyMerType'] = ''; // 代理商家类型
        $sources['proxyMerCredentials'] = ''; // 代理商家证件号码
        $sources['netType'] = '0'; // 渠道编号

        $source = implode('|', $sources);

        $fp = @stream_socket_client($this->sock_url, $errno, $errstr, 30);
        $retMsg="";

        if (!$fp) {
            $this->log->add('bocpay', "code: 999999\tmessage: $errstr");
            throw new Exception("支付网关错误：$errstr ($errno)", 1);
        } else {
            $in  = "<?xml version='1.0' encoding='UTF-8'?>";
            $in .= "<Message>";
            $in .= "<TranCode>cb2200_sign</TranCode>";
            $in .= "<MsgContent>".$source."</MsgContent>";
            $in .= "</Message>";
            fwrite($fp, $in);
            while (!feof($fp)) {
                $retMsg = $retMsg.fgets($fp, 1024);
            }
            fclose($fp);
        }

        $dom = new DOMDocument;
        @$dom->loadXML($retMsg);

        $retCode = $dom->getElementsByTagName('retCode');
        $retCode_value = $retCode->item(0)->nodeValue;
        
        $errMsg = $dom->getElementsByTagName('errMsg');
        $errMsg_value = $errMsg->item(0)->nodeValue;

        $signMsg = $dom->getElementsByTagName('signMsg');
        $signMsg_value = $signMsg->item(0)->nodeValue;

        $orderUrl = $dom->getElementsByTagName('orderUrl');
        $orderUrl_value = $orderUrl->item(0)->nodeValue;
        
        $MerchID = $dom->getElementsByTagName('MerchID');
        $merID = $MerchID->item(0)->nodeValue;

        if($retCode_value != "0") {
            $this->log->add('bocpay', "code: $retCode_value\tmessage: $errMsg_value");
            throw new Exception("支付网关错误，请稍后再试", 1);
        }else{

            add_filter('woocommerce_payment_successful_result', array($this, 'build_gateway_form'));

            $sources['merSignMsg'] = $signMsg_value;
            $sources['orderUrl'] = $orderUrl_value;
            $sources['issBankNo'] = '';
            return array(
                'result'   => 'success',
                'bocpay'   => 1,
                'redirect' => $order->get_checkout_payment_url( true ),
                'sources'  => $sources
            );
        }

    }

    /**
     * Output for the order received page.
     *
     * @access public
     * @return void
     */
    function receipt_page( $order ) {

        $result = $this->process_payment($order);
        echo $this->build_gateway_form( $result );
    }

    function build_gateway_form($result) {

        if (empty($result['bocpay'])){
            return;
        }

        // $order = new WC_Order($sources['orderid']);
        $sources = $result['sources'];
        foreach ($sources as $k => $v) {
            if ($k=='orderUrl') continue;
            $form.="<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n" ;
        }
        $p = '<html>'
            .'<body onload="form_pay.submit()">'
            .'<form name="form_pay" method="post" action="'.$sources['orderUrl'].'">'
            .$form
            .'</form>'
            .'</body>'
            .'</html>';
        // var_dump($this->notify_url);
        exit($p);
    }


    /**
     * Check for Bocpay IPN Response
     *
     * @access public
     * @return void
     */
    function check_bocpay_response() {

        if (empty($_POST['notifyMsg'])) {
            wp_die("Invalid Requirements");
        }
        $notifyMsg = $_POST['notifyMsg'];

        $fp = @stream_socket_client($this->sock_url, $errno, $errstr, 30);
        $retMsg="";
        $tranCode = "cb2200_verify";

        if (!$fp) {
            $this->log->add('bocpay', "code: 999999\tmessage: $notifyMsg");
            wp_die("Invalid FP");
        } else {
            $in  = "<?xml version='1.0' encoding='UTF-8'?>";
            $in .= "<Message>";
            $in .= "<TranCode>".$tranCode."</TranCode>";
            $in .= "<merchantID>".$this->merchantID."</merchantID>";
            $in .= "<MsgContent>".$notifyMsg."</MsgContent>";
            $in .= "</Message>";
            fwrite($fp, $in);
            while (!feof($fp)) {
                $retMsg = $retMsg.fgets($fp, 1024);
            }
            fclose($fp);
        }

        $dom = new DOMDocument;
        @$dom->loadXML($retMsg);
        $retCode = $dom->getElementsByTagName('retCode');
        $retCode_value = $retCode->item(0)->nodeValue;
    
        $errMsg = $dom->getElementsByTagName('errMsg');
        $errMsg_value = $errMsg->item(0)->nodeValue;

        if ($retCode_value!='0'){
            $this->log->add('bocpay', "code: retCode_value\tmessage: $notifyMsg");
            wp_die('Invalid Code');
        }

        $sources = explode('|', $notifyMsg);
        if ($sources[9]!='1'){
            $this->log->add('bocpay', "code: 999998\tmessage: $notifyMsg");
            wp_die('Payment Failure');
        }

        $order_id = $sources[5];
        $order = new WC_Order($order_id);
        if( $order->status != 'completed'){
            $order->payment_complete();
            $order->add_order_note ('支付成功');
            update_post_meta( $order_id, 'Bocpay Trade No.', wc_clean( $sources[8] ) );
            $this->log->add('bocpay', "code: 000000\tmessage: ".$notifyMsg);
            echo "Success";
            exit;
        }

    }

    /**
     * Complete order when customer release funds from BOC
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
    function clean( $str = ''){
        $clean = str_replace( array('%'), '', $str );
        $clean = sanitize_text_field( $clean );
        $clean = html_entity_decode(  $clean , ENT_NOQUOTES );
        return $clean;
    }

    /**
     * Display Bocpay Trade No. in the backend.
     * 
     * @access public
     * @param mixed $order
     * @since 1.3
     * @return void
     */
    function wc_bocpay_display_order_meta_for_admin( $order ){
        $trade_no = get_post_meta( $order->id, 'Bocpay Trade No.', true );
        if( !empty($trade_no ) ){
            echo '<p><strong>交通银行交易流水号：</strong><br />' .$trade_no. '</p>';
        }
    }
}
