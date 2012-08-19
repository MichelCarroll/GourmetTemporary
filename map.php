<div id="entreprise-map-area">

    <div id="map">

    </div>

    <div id="category-popup"><div class="block"><ul class="elements">

        <?php $categories = get_categories(array('hide_empty' => 0));
        foreach($categories as $cat) {
            if(!strstr($cat->slug, 'blog') && !strstr($cat->slug, 'uncategorized') && !strstr($cat->slug, 'marche')) {
                echo '<li><a class="map-categories" id="' . $cat->term_id . '">';
                echo '<img width="35" src="' . get_stylesheet_directory_uri() . '/images/business/category/' . $cat->slug . '.png" />';
                echo '<span class="description"><span class="title">';
                echo $cat->cat_name;
                echo '</span><span class="meta-information">';
                echo $cat->count . ' ' . __('Businesses');
                echo '</span></span>';
                echo '</a></li>';
            }
        } ?>

    </ul></div></div>

    <div class="header-bar">
        <a class="par-produits"><?php _e('Categories'); ?></a>

        

        <ul id="map-controls">
            <li><a onclick="javascript:window.print();"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/print.gif" /></a></li>
            <li><a class="addthis_button_email" href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/email.gif" /></a></li>
            <li><a><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link.gif" /></a></li>
        </ul>

        <div  class="activites" >
            <ul>
                <?php
                $terms = get_terms('legend_icons', array('hide_empty' => 0));
                foreach($terms as $term) {  ?>
                    <li><a id="<?php echo $term->term_id; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/business/activity/<?php echo $term->slug; ?>.gif"></a><span class="tooltip center skyblue"><?php echo $term->name; ?></span></li>
                    <?php } ?>
            </ul>
       </div>

    </div>
    <div class="header-bar-transparency"></div>
    
    <div class="footer-bar">
        <input id="my_location" type="text" class="initial dark-background" value="<?php echo __('Enter Your Location'); ?>" />
        <input id="locate_btn" type="button" value="" />
        
        <?php get_template_part( 'social-directory' ); ?>
    </div>
    <div class="footer-bar-transparency"></div>


    <?php
        $businesses = get_businesses_for_map();
        $categories = get_categories_for_map();
        $services = get_services_for_map();
    ?>


    <?php
    foreach($businesses as $business) {
        include('map-summary.php');
        include('map-description.php');
    }
    ?>

</div>


<script type="text/javascript">
    var theme_dir = "<?php echo get_stylesheet_directory_uri(); ?>";
    var businesses = <?php get_businesses_for_map('json'); ?>;
    var categories = <?php get_categories_for_map('json'); ?>;
    var services = <?php get_services_for_map('json'); ?>;

    var single_id = null;
    <?php if($_GET['localize_single']) { ?>
        single_id = <?php echo $_GET['localize_single']; ?>;
    <?php } ?>
</script>

