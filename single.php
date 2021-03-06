<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>


          <?php show_blog_breadcrumb(); ?>

              <div class="page" id="content">

              <div id="article">

                  <?php the_post(); ?>
                  <?php $catDet = get_the_category($post->ID);
	$color_values = "#8ec75e";
	$mainCatSlug = get_post_meta($post->ID,'post_main_category', TRUE);
	if(!empty($mainCatSlug))
	{
		$mainCat = get_category_by_slug( $mainCatSlug );
		$mainCatTermId = $mainCat->term_id;
		$color_name = "category_".$mainCatTermId."_color";
		$color_values = get_option( "$color_name");
	}
	 ?>
                  <h1 style="color:<?php echo $color_values; ?>"><?php the_title(); ?></h1>
                  <hr class="grayed">

                  <?php
                    $img_url = array(
                        get_stylesheet_directory_uri() . '/images/slider-default.jpg',
                        220, 120, false);
                    if(has_post_thumbnail ($post->ID)) {
                        $img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');
                    }
                  ?>
                  <img width="285" src="<?php echo $img_url[0]; ?>" class="feature-image">
                  <?php get_template_part('social', 'page'); ?>
                  <div class="clear"></div>

                  <hr class="dotted grayed">
                  <div class="meta-information grayed"><?php echo __('Keywords'); ?>: <?php echo implode(',', get_tags($post->ID)); ?></div>
                  <div class="meta-information grayed"><?php echo __('By') . ' ' . get_the_author(); ?> &bull; <?php the_date(); ?></div>
                  <hr class="dotted grayed">

                  <?php the_content(); ?>

                  </div>
              </div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>