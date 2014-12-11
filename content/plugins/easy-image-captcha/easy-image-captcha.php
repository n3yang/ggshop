<?php
/*
 * Plugin Name: Easy Image Captcha
 * Plugin URI: http://n3yang.com
 * Description: 
 * Version: 0.1
 * Author: n3yang
 * Author URI: http://n3yang.com
 * Requires at least: 3.8.1
 * Tested up to: 3.9.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* 
*/



function EasyImageCaptchaCreateImage() {

    require_once( plugin_basename( 'cool-php-captcha.php' ) );

    $eic = new EasyImageCaptcha;

    $eic->width = 90;
    $eic->height = 48;
    $eic->maxWordLength = 4;

    // Image generation
    $eic->CreateImage();

}

function EasyImageCaptchaGetCaptchaUrl() {
    return plugins_url('easy-image-captcha').'/captcha.php';
}

function EasyImageCaptchaCheckCaptcha($captcha) {
    $eic = new EasyImageCaptcha();
    return $eic->check($captcha);
}

function EasyImageCaptchaInit()
{
    require_once 'cool-php-captcha.php';
}
add_action( 'plugins_loaded', 'EasyImageCaptchaInit' );

add_filter( 'woocommerce_process_registration_errors', 'EasyImageCaptchaCheckCaptchaProcessRegistration', 9,  1);
function EasyImageCaptchaCheckCaptchaProcessRegistration($validation_error){
    if ( !empty( $_POST['register']) ){
        if ( !EasyImageCaptchaCheckCaptcha($_POST['captcha']) ){
            return new WP_Error('easy-image-captcha-error-captcha', '请输入正确的验证码');
        } else {
            return $validation_error;
        }
    }
}



