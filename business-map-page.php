<?php
/**
 * Template Name: Business Map Template
 * Description: A Page Template that shows a map of all the businesses on the website.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php the_post(); ?>

                                <?php get_template_part('map'); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>