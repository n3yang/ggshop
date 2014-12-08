<?php
/**
 * The template for displaying Search Results pages
 * 
 * @package WordPress
 * @subpackage Pinwu
 */
get_header();
$search_query = get_search_query();
                                        
$args = array(
    'posts_per_page'    => 8,
    'paged'             => $paged>1 ? $paged : 1,
    's'                 => $search_query,
    'post_type'         => 'product',
);
$posts = query_posts($args);

?>

    <div class="main list_bg">

        <div class="list_wrap">
            
            <div class="list_ad">
                
            </div>

            <div class="list_box base-clear">
                <?php get_template_part('category-list') ?>
                            
                <div class="list_con">
                    
                    <div class="list_item_wrap">
                        <ul>
                            <?php 
                            if (have_posts()){ while ( have_posts() ) : the_post(); global $product;
                            ?>
                            <li>
                                <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
                                <a href="<?php the_permalink(); ?>">
                                    <em>
                                        <?php the_post_thumbnail('shop_catalog') ?>
                                    </em>
                                    <div>
                                        <p>RMB: <strong><?php echo ($product->get_price()); ?></strong></p>
                                        <span><?php the_title() ?></span>
                                    </div>
                                </a>
                                <?php // do_action( 'woocommerce_after_shop_loop_item' ); ?>
                            </li>
                            <?php
                                endwhile;
                            } else {
                                echo '<div class="list_con" style="text-align: center"><h3>对不起，没有符合您需要的商品。</h3></div>';
                            }
                            ?>
                        </ul>

                        <!-- <div class="jogger"><a class="prev" href=""> &lt; 上一页</a> <span class="current">1</span><a href="#?page=2">2</a><a href="#?page=3">3</a><a href="#?page=4">4</a><a href="#?page=5">5</a><a href="#?page=6">6</a><a class="next" href="#?page=2">下一页 &gt; </a></div> -->


                            <?php ggshop_pagin_nav() ?>
                    </div>

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



<?php
get_footer();
?>