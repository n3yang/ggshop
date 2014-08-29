<?php
/**
 * The template for displaying Search Results pages
 * 
 * @package WordPress
 * @subpackage Pinwu
 */
get_header();
$search_query = get_search_query();
?>

	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style/products.css"/>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/products.js"></script>
    
	<!--ListOfProducts-->
    <div class="main">
    	
        <?php echo pinwu_get_ad_banner() ?>

        <div class="products base-clear">
        	<?php get_sidebar() ?>
            
            <div class="products-right">
            	<div class="products-list-wrap">
   
                    <div class="products-list-result">
                    	<div class="products-list-result-title">
                        	<div class="ti dib-wrap">
                            	<span class="dib"><a class="active">关键字搜索：<?php echo $search_query ?></a></span>
                            </div>
                        </div>
                        <div class="products-info">
                        	<div class="products-info-c">
                            	
                                <!--以下部分为文章内容-->
                                <div class="news-list">
                                    <ul>
                                    	<?php
                                    	$args = array(
                                    		'posts_per_page'    => 20,
                                    		'paged'             => $paged>1 ? $paged : 1,
                                            's'                 => $search_query,
                                    	);
										$posts = query_posts($args);
										if (have_posts()){ while(have_posts()) : the_post();
										?>
                                        <li>
                                            <a href="<?php the_permalink() ?>" target="_blank"><?php the_title() ?></a>
                                            <span><?php echo get_the_date() ?></span>
                                        </li>
                                    	<?php
                                            endwhile;
                                        } else {
                                            echo '没有查询到相关内容';
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <!--以上部分为文章内容-->                                
                                <p>
                                    <?php pingwu_pagin_nav(); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div> 


<?php
get_footer();
?>