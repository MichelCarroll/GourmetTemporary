<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

?>
<div id="sidebar">

      <div class="blue-banner small">
          <div class="left-side"></div>
          <div class="middle"><?php get_search_form(); ?></div>
          <!--<div class="right-side"></div>-->
      </div>
    

    <?php 
        $template = get_post_meta( $post->ID, '_wp_page_template', true );
        $business_directory_page = (is_archive() && !is_category('blog'));
        $static_website = (is_page() || is_category('blog'));
    ?>

        <?php

         if(($static_website && !($template == 'home-page.php')) || is_search() || get_post_type() == 'post') {
            dynamic_sidebar( 'sidebar-1' );
            //show_gourmet_pages();
         }
         else if ( $business_directory_page || !dynamic_sidebar( 'sidebar-1' ) ) { ?>

            <?php dynamic_sidebar( 'sidebar-3' ) ?>

        <?php } ?>

      <!--<div class="marron-banner big">
          <div class="left-side"></div>
          <div class="middle"><a href="<?php echo get_business_map_link(); ?>">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/map-image-<?php echo get_current_language_code(); ?>.png" />
          <span class="description">Voir la <br />carte interactive</span>
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/map-link-<?php echo get_current_language_code(); ?>.jpg" />
          </a></div>
          <div class="right-side"></div>
      </div>-->
			<?php if ( $business_directory_page  ) {  ?>
			 <?php dynamic_sidebar( 'adds-sidebar' ) ?>
             <?php } ?>
             
        <?php //if ( $business_directory_page || !dynamic_sidebar( 'sidebar-2' ) ) : ?>

            <!--<a class="ad_space_250"></a>-->

        <?php //endif; // end sidebar widget area ?>

</div><!-- #secondary .widget-area -->
