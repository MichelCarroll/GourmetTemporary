<?php
/**
 * Template Name: Home Template
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

          <div id="content" class="home">

          

             



              <div id="article">

                  <?php the_post(); ?>
                  <h1><?php the_title(); ?> <span class="subtitle"><?php echo get_post_meta($post->ID, 'subtitle', true); ?></span></h1>
                  <hr />
                  <?php the_content(); ?>
                  <blockquote>&laquo;<?php echo __('My region, I savour it!'); ?>&raquo;<br /><?php echo __('What a delicious idea!'); ?></blockquote>
                  
              </div>
      <?php include('starred-businesses.php'); ?>

            </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>