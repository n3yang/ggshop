<?php
/**
 * Displayed when no products are found matching the current query.
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/no-products-found.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

	<div class="main list_bg">

		<div class="list_wrap">
			
			<div class="list_ad">
				
			</div>

			<div class="list_box base-clear">
				<div class="list_menu">
					<ul>
						<li>
							<a href="/category/clothing">宫廷服饰</a>
						</li>
						<li>
							<a href="/category/electronics">宫廷数码</a>
						</li>
						<li>
							<a href="/category/home">宫廷家居</a>
						</li>
						<li>
							<a href="/category/study">宫廷文房</a>
						</li>
						<li>
							<a href="/category/sports">宫廷体育</a>
						</li>
						<li>
							<a href="/category/media">宫廷音画</a>
						</li>
						<li>
							<a href="/category/toys">宫廷童趣</a>
						</li>
					</ul>
				</div>
							
				<div class="list_con" style="text-align: center">
					
						<h3>没有找到符合要求的产品</h3>

				</div>


			</div>

		</div>

	</div>

<script>
	
$(function () {
	$(".list_item_wrap li").hover(function () {
		$(this).toggleClass("active")
	})
})

</script>