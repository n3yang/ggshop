<?php
/**
 * The template for displaying all pages
 *
 * @package Gugong
 * @subpackage GugongShop
 */

get_header(); ?>

<?php
/* The loop */
while ( have_posts() ) {
	the_post();
	the_content();
}
?>

<?php get_footer(); ?>