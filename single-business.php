<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

        <div id="content" class="directory">


                <?php twentyeleven_content_nav( 'nav-above' ); ?>

                <ul id="listing">
                    <?php the_post(); ?>
                    <?php get_template_part( 'content', 'business' ); ?>

                </ul>

                <?php twentyeleven_content_nav( 'nav-below' ); ?>

        </div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>