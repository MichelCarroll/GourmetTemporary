<div id="business-summary-<?php echo $business->id; ?>" class="map-business-summary cat-<?php echo get_business_main_category($business->id); ?>">

   <img class="cat-pic" width="55" src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/category/<?php echo get_business_main_category($business->id); ?>.png">
   <div class="summary-container themed">
        <?php echo '<h2 class="themed">' . $business->post_title . '</h2>'; ?>

        <ul class="coordinates">
            <?php if($business->phone) { ?><li class="telephone"><?php echo $business->phone; ?></li><?php } ?>
            <?php if($business->email) { ?><li class="email"><?php echo $business->email; ?></li><?php } ?>
            <?php if($business->website) { ?><li class="website"><?php echo $business->website; ?></li><?php } ?>
            <?php if($business->address) { ?><li class="address"><?php echo $business->address; ?></li><?php } ?>
        </ul>

       <div  class="activites" >
        <ul>
            <?php
        $terms = get_terms('legend_icons', array('hide_empty' => 0));
        foreach($terms as $term) {  ?>
            <li><a href="#<?php echo $term->slug; ?>"><img height="23" src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/activity/<?php echo $term->slug . (in_array($term->term_id, $business->services)?'':'-gray'); ?>.gif"></a></li>
            <?php } ?>
        </ul>
       </div>
    </div>
    <div class="down-arrow"></div>
</div>
