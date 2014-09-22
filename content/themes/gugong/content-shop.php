
	<div class="main">
		<div class="banner">
			<div id="ui-slide">
		        <ul class="slide">
		            <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/11.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/toys"></a>
		                </div>
		            </li>
		            <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/22.jpg">
		                <div class="slide-pic">
		             		<a style="position:absolute;width:1200px;height:367px;" href="category/media"></a>
		                </div>
		            </li>
		            <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/33.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/sports"></a>
		                </div>
		            </li>
		            <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/44.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/study"></a>
		                </div>
		            </li>
		             <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/55.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/home"></a>
		                </div>
		            </li>
		             <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/66.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/electronics"></a>
		                </div>
		            </li>
		             <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/77.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/clothing"></a>
		                </div>
		            </li>
		             <li class="slide-panel" data-pic="<?php bloginfo('template_url'); ?>/images/88.jpg">
		                <div class="slide-pic">
		                	<a style="position:absolute;width:1200px;height:367px;" href="category/foods"></a>
		                </div>
		            </li>
		        </ul>
		        <div class="slide-trigger dib-wrap">
		            <a class="dib a1 active"></a>
		            <a class="dib a2"></a>
		            <a class="dib a3"></a>
		            <a class="dib a4"></a>
		            <a class="dib a5"></a>
		            <a class="dib a6"></a>
		            <a class="dib a7"></a>
		            <a class="dib a8"></a>
		        </div>

		        <div class="slide-btn">
		        	<a class="prev" href="javascript:;"></a>
		        	<a class="next" href="javascript:;"></a>
		        </div>
		    </div>
		</div>
	</div>

<script>
(function($){
		
	//slide
	$.fn.slide = function(options){
		//设置默认参数
		var defaults = {    
  			aLi: this.find('.slide-panel'),
			aA: this.find('.slide-trigger a'),
			aBtn: this.find('.slide-btn a'),
			autoPlayTime: 3000,
			showTime: 1000,
			setInit: true
		};
		var play = null,
			page = 0;
		var opts = $.extend(defaults, options);
		//初始化slide
		if(opts.setInit) init();
	
		//初始化slide
		//设置宽slide li背景
		//设置内slide div.slide-pic背景
		function init(){
			opts.aLi.each(function(){
				$(this).css({
					'opacity': 0,
					'z-index': 0
				});
				$(this).find('.slide-pic').css('background-image', 'url('+ $(this).attr('data-pic') +')');
			});
			opts.aLi.eq(0).css({
				"opacity": 1,
				'z-index': 1
			}); 
		};
		
		function showSlide(){
			opts.aA.eq( page ).addClass( "active" ).siblings().removeClass( "active" );
			opts.aLi.eq( page ).css({ "opacity" : 1 });
			opts.aLi.eq( page ).siblings().animate( { "opacity" : 0 }, opts.showTime, function(){
				opts.aLi.eq( page ).css({"z-index":1}).siblings().css({"z-index":0});
			});
		};
		
		function playSlide(){
			if( !opts.aLi.is( ":animated" )){
				if( page == opts.aLi.length - 1 ){
					page = 0;
					showSlide();
				}else{
					page++;
					showSlide();
				}
			}
		};
		
		opts.aBtn.eq(1).click(function () {
			if( !opts.aLi.is( ":animated" )){
				if( page == opts.aLi.length - 1 ){
					page = 0;
					showSlide();
				}else{
					page++;
					showSlide();
				}
			}
		});

		opts.aBtn.eq(0).click(function () {
			if( !opts.aLi.is( ":animated" )){
				if( page == 0){
					page = opts.aLi.length - 1;
					showSlide();
				}else{
					page--;
					showSlide();
				}
			}
		});

		opts.aA.click(function(){
			if( !opts.aLi.is( ":animated" ) ){
				var index = opts.aA.index( this ); 
				page = index;
				showSlide();
			}
		});
		
		
		this.hover( function(){
			clearInterval(play);
		}, function(){
			play = setInterval(function(){
				playSlide();
			}, opts.autoPlayTime);
		}).trigger( "mouseleave" );
	};
	
	
})(jQuery);


$(document).ready(function() {
	$('#ui-slide').slide({
		autoPlayTime: 10000,
		showTime: 1000
	});

});
</script>