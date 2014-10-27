<?php
/**
 * GugongShop functions and definitions
 * 
 * @package WordPress
 * @subpackage 故宫商城
 */


// turn off auto update
remove_action( 'wp_version_check', 'wp_version_check' );
add_filter( 'pre_site_transient_update_core', function(){return;});
remove_action('wp_head', 'wp_generator');
// turn off links in header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');


// add user's tel in user pannel
// add_filter('user_contactmethods', function ($user_contactmethods){
//     $user_contactmethods ['tel'] = '电话';
//     return $user_contactmethods ;
// });

// echo z_taxonomy_image_url($cat->term_id);



// add_theme_support('post-thumbnails');
// add_theme_support('post-formats', array('video') );
// add_theme_support('post-formats',array('gallery'));


//MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'secondary-image');






/********** customize the admin pannel ***********/
add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page(){
	// add_menu_page( 'custom menu title', 'custom menu', 'manage_options', '/aa/test.php', '', '', 8 );

	// post page
	remove_meta_box('commentstatusdiv', 'post', 'normal');
	remove_meta_box('commentsdiv', 'post', 'normal');
	remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
	remove_meta_box('trackbacksdiv', 'post', 'normal');

	// customize dashboard
	remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'normal');  // Recent Drafts
	remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
	remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
	remove_meta_box('welcome_panel', 'dashboard', 'normal');   // Other WordPress News

	add_filter('admin_footer_text', function(){});
	add_filter('update_footer', function(){echo '感谢使用';});

	add_theme_support( 'woocommerce' );
}
// remove the logo in admin login page
add_action('login_head', function(){
	echo '<style type="text/css">h1 a {background-image: none !important; }</style>';
});
// show admin bar never
show_admin_bar(false);
// remove some nodes in admin bar
add_action( 'admin_bar_menu', 'remove_wp_admin_bar', 999 );
function remove_wp_admin_bar( $wp_admin_bar ) {
	$wp_admin_bar->remove_node('wp-logo');
	$wp_admin_bar->remove_node('comments');
	$wp_admin_bar->remove_node('updates');
}


add_action( 'load-index.php', function () {
	update_user_meta( get_current_user_id(), 'show_welcome_panel', 0 );
});


add_action('init', function(){
	wp_enqueue_style('jquery-ui-style', get_template_directory_uri().'/css/jquery-ui.css');
}, 0);


/********** customize the admin panel **********/




/**
 * print the page bar
 * @param  integer $range 
 * @return bool    all ways true
 */
function ggshop_pagin_nav($range = 4){
	global $wp_query;
	$paged = get_query_var('paged');
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	echo '<div class="jogger">';
	if($paged != 1){echo "<a href='" . get_pagenum_link(1) . "' class='extend'>首页</a>";}
	previous_posts_link('上页');
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	next_posts_link('下页');
    if($paged != $max_page){echo "<a href='" . get_pagenum_link($max_page) . "' class='extend'>尾页</a>";}
    echo '</div>';}
}



/************ woocommerce 定制 *************/

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10);

// add_rewrite_rule('/account/reg', 'index.php?page_id=7', 'top');
// add_filter( 'query_vars', function(){return array('aaa');}, 10 );
// add_rewrite_endpoint('aaa', EP_PAGES);


add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
	global $woocommerce;
	
	if ( $_SERVER['REQUEST_URI'] == '/cart?empty-cart' ) {
		$woocommerce->cart->empty_cart();
	}
}


add_filter('woocommerce_cart_totals_coupon_html', function($value){
	if (preg_match('/<span class="amount">(.*)<\/span>/', $value, $matches)) {
		$value = str_replace("&yen;&nbsp;", "&yen;&nbsp;-", $matches[1]);
		return $value;
	}
});

add_action('woocommerce_checkout_process', function(){
	$checkout = WC()->checkout();
	foreach ($checkout->checkout_fields['billing'] as $key => $field) {
		$checkout->checkout_fields['billing'][$key]['required'] = 0;
	}
	$checkout->checkout_fields['shipping']['shipping_country']['required']  = 0;
	$checkout->checkout_fields['shipping']['shipping_city']['required']     = 0;
	$checkout->checkout_fields['shipping']['shipping_state']['required']    = 0;
	$checkout->checkout_fields['shipping']['shipping_postcode']['required'] = 0;
});

add_action('woocommerce_before_checkout_form', function(){
	global $current_user;
	if ($current_user->ID==0){
		wp_redirect('/account?login');
	}
});

function get_the_product_image_html($product){

	$attachment_ids = $product->get_gallery_attachment_ids();
	if (empty($attachment_ids)) {
		return;
	}

	foreach ( $attachment_ids as $attachment_id ) {
		$image = wp_get_attachment_image( $attachment_id , 'shop_single' );
		if ( ! $image )
			continue;
		$image_title = esc_attr( get_the_title( $attachment_id ) );
		$image_single = wp_get_attachment_image_src( $attachment_id );
		return apply_filters( 'woocommerce_single_product_image_html', 
			sprintf( '%s', $image ), 
			$attachment_id, 
			$product->ID );
		break;
	}
}