<div class="user_center_menu">
				<h2>
					<img src="<?php bloginfo('template_url') ?>/images/tx.jpg" alt="">
				</h2>
				<h3><?php echo wp_get_current_user()->display_name; ?></h3>
				<dl>
					<dt>订单中心</dt>
					<dd><a href="/ucp/order">我的订单</a></dd>
					<!--
					<dd><a href="#">未付款的订单</a></dd>
					<dd><a href="#">退换货</a></dd>
					-->
				</dl>
				<dl>
					<dt>个人中心</dt>
					<dd><a href="/ucp/address">我的地址</a></dd>
					<!-- <dd><a href="#">我的代金卷</a></dd> -->
					<!-- <dd><a href="#">我的评价</a></dd> -->
					<dd><a href="/ucp/favorite">我的收藏</a></dd>
				</dl>
				<dl>
					<dt>帐号设置</dt>
 					<!--
 					<dd><a href="/ucp/info">基本资料</a></dd>
					<dd><a href="#">登录绑定</a></dd>
					-->
					<dd><a href="/ucp/avatar"></a></dd>
					<dd><a href="/ucp/password">修改密码</a></dd>
				</dl>
			</div>