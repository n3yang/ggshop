<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Wechatpay Payment Gateway
 *
 * Wechatpay Payment Gateway for Woocommerce
 * 采用微信原生支付的模式2：http://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=6_5
 *
 * @class         WC_Wechatpay
 * @extends       WC_Payment_Gateway
 * @version       1.0
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

        add_action( 'woocommerce_thankyou_wechatpay', array( $this, 'thankyou_page_payment_message') );
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
        // 显示支付结果信息在 thankyou_page_payment_message 方法
        return;
    }

    function thankyou_page_payment_message( $orderId ) {

        $order = new WC_Order($orderId);
        if ($order->needs_payment()) {
            $message = '<p>支付结果正在确认中。</p>'
                .'<p>如您已确认成功支付，请刷新本页，查看最新的支付结果。</p>';
        } else {
            $message = '<p>支付完成</p>';
        }
        echo '<div class="wechatpay-payment-message">' . $message . '</div>';
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

        // 获取订单及产品信息
        $order = new WC_Order($orderId);

        if (!$order->needs_payment()){
            return;
        }

        // 调用统一下单API，生成预支付交易
        // 统一下单API URL
        $unifiedOrderUrl = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        // 支付时显示的标题信息
        $bodyStr = get_bloginfo('name').'订单 #'.$orderId;
        // 详细商品描述
        $items = $order->get_items();
        $detailStr = '';
        foreach ($items as $key => $item) {
            $detailStr .= $item['name'] . ' x '.$item['qty'] . "\n";
        }
        // 预支付订单信息
        $preData = array(
            'appid'             => $this->appId,
            'mch_id'            => $this->merchantId,
            'nonce_str'         => $this->build_noncestr(),
            'body'              => $bodyStr,
            'detail'            => $detailStr,
            'out_trade_no'      => date('YmdHis').$orderId,
            'total_fee'         => $order->get_total() * 100,
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],
            'notify_url'        => $this->notify_url,
            'trade_type'        => 'NATIVE',
            'product_id'        => $orderId,
            );
        $preData['sign'] = $this->build_sign($preData);
        // to xml
        $xml = $this->array_to_xml($preData);
        $rdata = $this->post_xml_curl($xml, $unifiedOrderUrl);
        // TODO:校验返回数据
        $rdata = $this->xml_to_array($rdata);
        if ($rdata['return_code'] != 'SUCCESS') {
            $this->log('Unifiedorder faild. return_code:'.$rdata['return_code'] . ' return_msg:'.$rdata['return_msg']);
            echo '微信支付平台貌似出现了问题，请稍后再试';
            return;
        }
        if ($rdata['result_code']) {

        }
        $returnUrl = $this->get_return_url($order);
        echo '<div class="wechatpay-qrcode-wrap">';
        echo '<p>请使用微信的“扫一扫”功能扫描下面的二维码图片</p>';
        echo $this->build_qrcode_image( $rdata['code_url'] );
        echo "<p><a href=\"$returnUrl\">在微信支付完成后，请点击此处，查看支付结果</a></p>";
        echo '</div>';
    }

    /**
     * 构建微信支付扫描的二维码
     * @param  int     $bizurl  支付地址
     * @return string           二维码图片的html标签
     */
    function build_qrcode_image($bizurl) {

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

        $postraw = file_get_contents("php://input");
        if (empty($postraw)) {
            wp_die("Invalid Requirements");
        }
        if ($this->debug) {
            $this->log('Reviece response:' . $postraw, 'Info');
        }

        // 验证数据信息
        $vdata = $this->verify_response($postraw);
        if ($vdata == false){
            $this->log('Wechatpay response verify faild! post:'.$postraw);
            wp_die("Invalid Requirements");
        }
        if ($this->debug) {
            $this->log('Wechatpay is verified', 'Info');
        }

        // 支付成功
        if ($vdata['return_code'] == 'SUCCESS' || $vdata['result_code'] == 'SUCCESS') {

            $orderId = substr($vdata['out_trade_no'], 14, strlen($vdata['out_trade_no']));
            $order = new WC_Order($orderId);

            if( $order->status != 'completed'){
                $order->payment_complete();
                $order->add_order_note ('支付成功');
                update_post_meta( $orderId, 'Wechatpay Trade No.', wc_clean( $vdata['transaction_id'] ) );
                $this->log('Payment Completed! Order ID: ' . $orderId , 'Info');
                // return xml message to wechatpay
                header( 'HTTP/1.1 200 OK' );
                $response = array(
                    'return_code'   => 'SUCCESS',
                    'return_msg'    => ''
                    );
                echo $this->array_to_xml($response);
                exit;
            }
        } else {
            $this->log('Wechatpay response faild! Return message: ' . $vdata['return_msg'] 
                . ' Result code: ' . $vdata['return_code']
                . ' Result message: ' . $vdata['return_msg'] );
            wp_die('');
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
     * 验证成功返回解析后的数组，否则返回false
     * 
     * @access public
     * @param string $postraw
     * @since 1.3
     * @return bool/string 验证成功返回解析后的数组，否则返回false
     */
    function verify_response($postraw) {
        if (empty($postraw)) {
            return false;
        }
        $postdata = $this->xml_to_array($postraw);
        // build signature string
        return $this->build_sign($postdata) == $postdata['sign'] ? $postdata : false;
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
     * 将数组转换为 key=value 的形式并使用 & 连接
     * 
     * @param  array $params 
     * @param  bool $passSign
     * @return string         
     */
    function build_params_str($params)
    {
        ksort($params);
        foreach ($params as $key => $value) {
            $params_str .= sprintf("%s=%s&", $key, $value);
        }
        return substr($params_str, 0, strlen($params_str)-1);
    }
    function build_sign($params, $passSign=true)
    {
        if (key_exists('sign', $params) && $passSign) {
            unset($params['sign']);
        }
        $str = $this->build_params_str($params).'&key='.$this->merchantKey;
        return strtoupper(md5($str));
    }
    /**
     * 产生一个随机只包含大小字母写及数字的字符串
     * @param  integer $length 
     * @return string
     */
    function build_noncestr( $length = 16 ) {  
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ( $i = 0; $i < $length; $i++ )  {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }
    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $timeout   url执行超时时间，默认30s
     * @throws WxPayException
     */
    static function post_xml_curl($xml, $url, $useCert = false, $timeout = 30)
    {       
        //初始化curl        
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        if ($useCert){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch, CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->signCert);
            curl_setopt($ch, CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->verifyCert);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            $this->log('CURL is failure. Error number: '.$error);
        }
    }
    /**
     * 将微信平台返回的xml数据转换为数组
     * @param  string $xml 
     * @return array      
     */
    function xml_to_array($xml)
    {
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($xml), true);
    }
    /**
     * 将数组转换为xml
     * @param  array $dataset 
     * @return           
     */
    function array_to_xml($dataset)
    {
        if (empty($dataset))
            return '<xml></xml>';
        $xml = '';
        foreach ($dataset as $k => $v) {
            if (is_numeric($v)){
                $xml .= "<" . $k . ">" . $v . "</" . $k . ">";
            }else{
                $xml .= "<" . $k . "><![CDATA[" . $v . "]]></" . $k . ">";
            }
        }
        $xml = '<xml>'.$xml.'</xml>';
        return $xml;
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

