<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<li class="page-summary">
    <div class="post">
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
      <h2><a href="<?php echo get_permalink($post->ID); ?>" style="color:<?php echo $color_values; ?>"><?php the_title(); ?></a></h2>
      <hr style="background-color:<?php echo $color_values; ?>; border-color:<?php echo $color_values; ?>">
      <?php
            $img_url = array(
                get_stylesheet_directory_uri() . '/images/business-default.jpg',
                220, 120, false);
            if(has_post_thumbnail ($post->ID)) {
                $img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');
            }
        ?>
      <img src="<?php echo $img_url[0]; ?>" class="business" width="220" />
      <?php the_excerpt(); ?>
      <div class="clear"></div>
      <hr class="dotted" style="background:<?php echo $color_values; ?>">
      <div class="meta-information"><?php echo __('Mots clés'); ?>: <span class="keywords" style="color:<?php echo $color_values; ?>"><?php echo implode(',', get_tags($post->ID)); ?></span></div>
      <div class="meta-information"><?php echo __('Par') . ' ' . get_the_author(); ?> &bull; <span class="brownify" style="color:<?php echo $color_values; ?>"><?php echo get_the_date(); ?></span></div>
      <div class="more"><a href="<?php echo get_permalink($post->ID); ?>" style="color:<?php echo $color_values; ?>">» <?php echo __('lire la suite'); ?></a></div>
      <hr class="dotted" style="background:<?php echo $color_values; ?>">
    </div>
</li>
