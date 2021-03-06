<?php
/* banner-php */
/**
 * Template Name: Home Page
 */

if ( post_password_required() ) {
    get_template_part( 'template-parts/page/protected', 'page' );
    return;
}

get_header(); ?>

<?php while(have_posts()) : the_post(); ?>

	<?php the_content(); ?>
	<?php mt_link_pages(); ?>

<?php endwhile; ?>   
<?php 
    get_footer( );
