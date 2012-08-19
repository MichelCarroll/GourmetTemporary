<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
		
        <div id="content" class="directory">

        <?php

        query_posts(  $query_string);

        if ( have_posts() ) : ?>

                <?php twentyeleven_content_nav( 'nav-above' ); ?>

                <ul id="listing">
                <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', get_post_type() ); ?>

                <?php endwhile; ?>
                </ul>

                <?php twentyeleven_content_nav( 'nav-below' ); ?>

        <?php else : ?>

                 <h1 class="no-found entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
                <div class="entry-content">
                        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
                </div><!-- .entry-content -->

        <?php endif; ?>

        </div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>