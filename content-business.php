<?php 
	$catDet = get_the_category($post->ID);
	$color_values = "#8ec75e";
	$mainCatSlug = get_business_main_category($post->ID);
	if(!empty($mainCatSlug))
	{
		$mainCat = get_category_by_slug( $mainCatSlug );
		$mainCatTermId = $mainCat->term_id;
		$color_name = "category_".$mainCatTermId."_color";
		$color_values = get_option( "$color_name");
	}
?>

  <li class="business-summary">
  <?php $businessOrder = business_order($post_ID = $post->ID,$post_type = 'business');?>

      <h2><a href="<?php echo return_business_permalink($post->ID); ?>" style="color:<?php print $color_values; ?>"><?php the_title(); ?></a></h2>

      <hr class="count" style="background-color:<?php print $color_values; ?>" />

    <?php

        $categories = wp_get_post_categories( $post->ID );

    ?>

    <ul class="categories">

        <?php foreach($categories as $cat) { $slug = get_cat_slug($cat); ?>

        <li><img width="55" src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/category/<?php echo $slug; ?>.png"></li>

        <?php } ?>

    </ul>

    <div class="post">

        <?php

            $img_url = array(

                get_stylesheet_directory_uri() . '/images/business-default.jpg',

                220, 120, false);

            if(has_post_thumbnail ($post->ID)) {

                $img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');

            }

        ?>

        <img src="<?php echo $img_url[0]; ?>" class="business" width="220">

        <?php the_content(); ?>

    </div>

    <?php get_template_part( 'social', 'directory' ); ?>

    <div class="clear"></div>

    <div class="details" style="background-color:<?php print $color_values; ?>">

        <ul class="coordinates">

            <?php

                $phone = get_post_meta($post->ID, '_business_phone', true);

                $address = get_post_meta($post->ID, '_business_address', true);

                $website = get_post_meta($post->ID, '_business_website', true);

            ?>

            <?php if($phone) { ?><li class="telephone"><?php echo $phone; ?></li><?php } ?>

            <?php if($address) { ?><li class="address"><?php echo $address; ?></li><?php } ?>

            <?php if($website) { ?><li class="website"><a href="<?php echo format_website($website); ?>"><?php echo $website; ?></a></li><?php } ?>

        </ul>

        <div class="activites">

            <?php $my_terms = get_key_array(get_the_terms( $post->ID, 'legend_icons'), 'term_id', true); ?>

            <?php $terms = get_terms('legend_icons', array('hide_empty' => 0));  ?>

            <ul>

                <?php foreach($terms as $term) {  ?>

                <li><a href="<?php echo get_term_link($term); ?>"><img height="23" src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/activity/<?php echo $term->slug . (in_array($term->term_id, $my_terms)?'':'-gray'); ?>.gif"></a><?php echo (in_array($term->term_id, $my_terms)?'<span class="tooltip center skyblue">' . $term->name . '</span></li>':''); ?>

                <?php } ?>

            </ul>

            <a class="locate" href="<?php echo return_business_permalink($post->ID); ?>"><?php _e('Find on the map'); ?></a>

        </div>

        <div class="clear"></div>

    </div>

  </li>