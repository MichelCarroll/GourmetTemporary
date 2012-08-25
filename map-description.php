<?php 
	$catDet = get_the_category($business->id);
	$color_values = "#8ec75e";
	$mainCatSlug = get_business_main_category($business->id);
	if(!empty($mainCatSlug))
	{
		$mainCat = get_category_by_slug( $mainCatSlug );
		$mainCatTermId = $mainCat->term_id;
		$color_name = "category_".$mainCatTermId."_color";
		$color_values = get_option( "$color_name");
	}
?>

<div id="business-popup-<?php echo $business->id; ?>" class="map-business-popup cat-<?php echo get_business_main_category($business->id); ?>">

    <div class="title-banner">
        <?php echo '<h2 style="color:'.$color_values.'">' . $business->post_title . '</h2>'; ?>
    </div>

    <div class="content-container">
        <?php echo $business->content; ?>

        <p><em><?php echo $business->hours; ?></em></p>
    </div>
    <?php
        $img_url = array(
            get_stylesheet_directory_uri() . '/images/slider-default.jpg',
            220, 220, false);
        if(has_post_thumbnail ($business->id)) {
            $img_url = wp_get_attachment_image_src( get_post_thumbnail_id($business->id), 'medium');
        }
    ?>
    <img width="220" src="<?php echo $img_url[0]; ?>" class="business">
    
    
    <div class="foot-banner">

        <ul class="coordinates">
            <?php if($business->address) { ?><li class="address" style="color:<?php echo $color_values; ?>"><?php echo $business->address; ?></li><?php } ?>
            <?php if($business->phone) { ?><li class="telephone" style="color:<?php echo $color_values; ?>"><?php echo $business->phone; ?></li><?php } ?>
            <?php if($business->email) { ?><li class="email"><a  style="color:<?php echo $color_values; ?>" href="<?php echo 'mailto:'. $business->email; ?>"><?php echo $business->email; ?></a></li><?php } ?>
            <?php if($business->website) { ?><li class="website"><a style="color:<?php echo $color_values; ?>" href="<?php echo format_website($business->website); ?>"><?php echo $business->website; ?></a></li><?php } ?>
        </ul>

       <div  class="activites" >
        <ul>
            <?php
        $terms = get_terms('legend_icons', array('hide_empty' => 0));
        foreach($terms as $term) {  ?>
            <li><a id="<?php echo $term->term_id; ?>"><img height="23" src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/activity/<?php echo $term->slug . (in_array($term->term_id, $business->services)?'':'-gray'); ?>.gif"></a>
                <?php if(in_array($term->term_id, $business->services)) { ?><span class="tooltip center skyblue"><?php echo $term->name; ?></span><?php } ?></li>
            <?php } ?>
        </ul>
       </div>
    </div>
    <div class="close"></div>
</div>
