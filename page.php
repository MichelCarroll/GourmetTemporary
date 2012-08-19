<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

        <?php show_page_breadcrumb(); ?>
        <div id="content" class="blog">

        <?php
            the_post();
        ?>

            <div id="article">

                  <h1><?php the_title(); ?></h1>
                  <hr class="grayed">

                  <?php the_content(); ?>

            </div>

        </div><!-- #content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>