<?php
/**
 * Twenty Eleven functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyeleven_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

define('BUSINESS_META_PREFIX', '_business_');


add_action( 'init', 'create_business_post_type' );

function create_business_post_type() {

    register_business_requirements();

    register_post_type( 'business',
            array(
                    'labels' => array(
                            'name' => __( 'Businesses' ),
                            'singular_name' => __( 'Business' ),
                            'add_new' => __( 'Add New' ),
                            'add_new_item' => __( 'Add New Business' ),
                            'edit' => __( 'Edit' ),
                            'edit_item' => __( 'Edit Business' ),
                            'new_item' => __( 'New Business' ),
                            'view' => __( 'View Business' ),
                            'view_item' => __( 'View Business' ),
                            'search_items' => __( 'Search Business' ),
                            'not_found' => __( 'No businesses found' ),
                            'not_found_in_trash' => __( 'No businesses found in Trash' ),
                            'parent' => __( 'Parent Business' )

                    ),
                    'rewrite' => array('slug' => 'entreprises'),
                    'has_archive' => true,
                    'description' => __( 'Businesses to be shown on the site listing, and on the regional map.' ),
                    'public' => true,
                    'supports' => array( 'title', 'editor', 'thumbnail','excerpt' ),
                    'menu_icon' => get_stylesheet_directory_uri() . '/images/plate.png',
                    'taxonomies' => array('category','legend_icons'),
                    'register_meta_box_cb' => 'add_business_metaboxes'
            )
    );
}


//ASSIGN SPECIFIC TAXONOMY AND OTHER THINGS THAT BUSINESSES REQUIRES
function register_business_requirements() {
    register_taxonomy(
        'legend_icons',
        'business',
        array(
            'hierarchical' => true,
            'label' => __('Legend Icons'),
            'query_var' => true,
            'rewrite' => true,
            'labels' => array(
                'name' => __( 'Legend Icons'),
                'singular_name' => __( 'Legend Icon'),
                'search_items' =>  __( 'Search Icons' ),
                'popular_items' => __( 'Popular Icons' ),
                'all_items' => __( 'All Icons' ),
                'parent_item' => 'Parent Icon',
                'parent_item_colon' => 'Parent Icon:',
                'edit_item' => __( 'Edit Icon' ),
                'update_item' => __( 'Update Icon' ),
                'add_new_item' => __( 'Add New Icon' ),
                'new_item_name' => __( 'New Icon Name' ),
                'separate_items_with_commas' => __( 'Separate icons with commas' ),
                'add_or_remove_items' => __( 'Add or remove icons' ),
                'choose_from_most_used' => __( 'Choose from the most used icons' ),
                'menu_name' => __( 'Legend Icons' ),
              )
        )
    );
}


//ASSIGN CUSTOM FIELDS TO BUSINESSES BY USING META BOXES
function add_business_metaboxes() {
    add_meta_box('pd_business_coordinates', __('Coordinates'), 'pd_business_coordinates', 'business', 'normal', 'high');
    add_meta_box('pd_business_display', __('Business Display'), 'pd_business_display', 'business', 'normal', 'high');
}
add_action( 'add_meta_boxes', 'add_post_metaboxes' );
function add_post_metaboxes() {
    add_meta_box('pd_post_display', __('Main Category'), 'pd_post_display', 'post', 'normal', 'high');
}
function pd_post_display() {
    global $post;

    echo '<input type="hidden" name="post_feat_noncename" id="post_feat_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    //$featured =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'featured', true);
    $main_cat =    get_post_meta($post->ID, 'post_main_category', true);

    echo '<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table"><tbody>';

        $categories = wp_get_post_categories( $post->ID );
        
        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="post_main_category">' . __('Main Category') . '</lable></th>';
        echo '<td><select ' . (!count($categories)?' disabled="disabled" ':'') . ' name="post_main_category">';
        echo '<option value=""></option>';
        foreach($categories as $cat_id) {
            $category = get_category($cat_id);
            echo '<option ' . ( $category->slug == $main_cat ? 'selected="selected" ':'') . ' value="'
                    . $category->slug . '">' . $category->name . '</option>';
        }
        echo '</select></td></tr>';

    echo '</tbody></table>';
}

add_action( 'save_post', 'pd_post_save_featured' );
function pd_post_save_featured($post_id) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

  if ( !wp_verify_nonce( $_POST['post_feat_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  if ( 'post' != $_POST['post_type'] || !current_user_can( 'edit_page', $post_id ))
      return;

    $meta_key = 'post_main_category';
    $value = $_POST['post_main_category'];
    if(get_post_meta($post_id, $meta_key, FALSE)) { // If the custom field already has a value
        update_post_meta($post_id, $meta_key, $value);
    } else { // If the custom field doesn't have a value
        add_post_meta($post_id, $meta_key, $value);
    }
}

//ASSIGN HTML TO CUSTOM FIELDS
function pd_business_coordinates() {
    global $post;
    
    echo '<input type="hidden" name="business_coord_noncename" id="business_coord_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
 
    $phone =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'phone', true);
    $email =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'email', true);
    $website =  get_post_meta($post->ID, BUSINESS_META_PREFIX.'website', true);
    $address =  get_post_meta($post->ID, BUSINESS_META_PREFIX.'address', true);
    $hours =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'hours', true);

    echo '<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table"><tbody>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_phone">' . __('Phone Number') . '</lable></th>';
        echo '<td><input type="text" style="width: 95%" value="' . $phone  .
            '" size="50" id="link_image" class="code" name="business_coord[phone]"></td></tr>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_email">' . __('Email') . '</lable></th>';
        echo '<td><input type="text" style="width: 95%" value="' . $email  .
            '" size="50" id="link_image" class="code" name="business_coord[email]"></td></tr>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_website">' . __('Website') . '</lable></th>';
        echo '<td><input type="text" style="width: 95%" value="' . $website  .
            '" size="50" id="link_image" class="code" name="business_coord[website]"></td></tr>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_address">' . __('Address') . '</lable></th>';
        echo '<td><input type="text" style="width: 95%" value="' . $address  .
            '" size="50" id="link_image" class="code" name="business_coord[address]"></td></tr>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_hours">' . __('Open Hours') . '</lable></th>';
        echo '<td><input type="text" style="width: 95%" value="' . $hours  .
            '" size="50" id="link_image" class="code" name="business_coord[hours]"></td></tr>';


    echo '</tbody></table>';
}

function pd_business_display() {
    global $post;

    echo '<input type="hidden" name="business_feat_noncename" id="business_feat_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    $featured =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'featured', true);
    $main_cat =    get_post_meta($post->ID, BUSINESS_META_PREFIX.'main_category', true);

    echo '<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table"><tbody>';

        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_featured">' . __('Featured') . '</lable></th>';
        echo '<td><input type="checkbox" value="1" '.($featured?' checked="checked"':'').
            ' size="50" id="link_image" style="width:auto;" class="code" name="business_featured"></td></tr>';

        $categories = wp_get_post_categories( $post->ID );
        
        echo '<tr class="form-field"><th valign="top" scope="row">';
        echo '<label for="business_main_category">' . __('Main Category') . '</lable></th>';
        echo '<td><select ' . (!count($categories)?' disabled="disabled" ':'') . ' name="business_main_category">';
        echo '<option value=""></option>';
        foreach($categories as $cat_id) {
            $category = get_category($cat_id);
            echo '<option ' . ( $category->slug == $main_cat ? 'selected="selected" ':'') . ' value="'
                    . $category->slug . '">' . $category->name . '</option>';
        }
        echo '</select></td></tr>';

    echo '</tbody></table>';
}

add_action( 'save_post', 'pd_business_save_coordinates' );
function pd_business_save_coordinates($post_id) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

  if ( !wp_verify_nonce( $_POST['business_coord_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  if ( 'business' != $_POST['post_type'] || !current_user_can( 'edit_page', $post_id ))
      return;

  foreach ($_POST['business_coord'] as $key => $value) {

        $meta_key = BUSINESS_META_PREFIX.$key;
        
        if(get_post_meta($post_id, $meta_key, FALSE)) { // If the custom field already has a value
            update_post_meta($post_id, $meta_key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post_id, $meta_key, $value);
        }
        
  }
}

add_action( 'save_post', 'pd_business_save_featured' );
function pd_business_save_featured($post_id) {

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

  if ( !wp_verify_nonce( $_POST['business_feat_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  if ( 'business' != $_POST['post_type'] || !current_user_can( 'edit_page', $post_id ))
      return;

    $meta_key = BUSINESS_META_PREFIX.'featured';
    $value = $_POST['business_featured'];
    if(get_post_meta($post_id, $meta_key, FALSE)) { // If the custom field already has a value
        update_post_meta($post_id, $meta_key, $value);
    } else { // If the custom field doesn't have a value
        add_post_meta($post_id, $meta_key, $value);
    }

    $meta_key = BUSINESS_META_PREFIX.'main_category';
    $value = $_POST['business_main_category'];
    if(get_post_meta($post_id, $meta_key, FALSE)) { // If the custom field already has a value
        update_post_meta($post_id, $meta_key, $value);
    } else { // If the custom field doesn't have a value
        add_post_meta($post_id, $meta_key, $value);
    }
}
function new_excerpt_more($more) {
return ' [...]';
}
add_filter('excerpt_more', 'new_excerpt_more');

function show_blog_breadcrumb() {
    global $post;
    echo '<div id="breadcrumb" class="test">';
    echo '<a href="' . get_site_url() . '">Acceuil</a>';
    $parent = get_gourmet_blog_page();
    echo ' &gt; <a href="' . get_permalink($parent->ID) . '">' . $parent->post_title . '</a>';
    echo ' &gt; <a href="' . get_permalink($post->ID) . '" class="active" >' . $post->post_title . '</a>';
    echo '</div>';
}

function get_gourmet_blog_page() {
    $page_title = (get_current_language_code() == 'fr'?'Évènements':'Activities');
    return get_page_by_title($page_title);
}

function show_page_breadcrumb() {
    global $post;
    echo '<div id="breadcrumb" class="page">';
    echo '<a href="' . get_site_url() . '">Acceuil</a>';
    if($post->post_parent){
        $parent = get_post($post->post_parent);
        echo ' &gt; <a href="' . get_permalink($parent->ID) . '">' . $parent->post_title . '</a>';
    }
    echo ' &gt; <a href="' . get_permalink($post->ID) . '" class="active" >' . $post->post_title . '</a>';
    echo '</div>';
}

function get_business_map_link() {
    return get_bloginfo('siteurl') . (get_current_language_code() == 'fr'?'/carte-des-entreprises/':'business-map/');
}

function show_gourmet_pages() {
    global $post;
    
    $pages = array();
    if(get_current_language_code() == 'en') {
        foreach(array(303,316,309,305) as $id)
            $pages[] = get_page($id);
    }
    else {
        foreach(array(230,232,234,237) as $id)
            $pages[] = get_page($id);
    }
    
    
	?><aside class="widget widget_pages" id="pages"><h3 class="widget-title">Pages</h3>
        <ul><?php

        foreach($pages as $page) {
            echo '<li class="page_item"><a title="' . $page->post_title . '" href="' . get_permalink($page->ID) . '">' . $page->post_title . '</a></li>';
            if($page->ID == $post->ID || $page->ID == $post->post_parent) {
                $children = get_pages(array('child_of' => $page->ID));
                foreach($children as $child) {
                    echo '<li class="page_item children"><a title="' . $child->post_title . '" href="' . get_permalink($child->ID) . '">' . $child->post_title . '</a></li>';
                }
            }
        }
        ?></ul>
    </aside><?php
    
}

function return_business_permalink($post_id) {
    $ret_url = get_bloginfo('siteurl');
    if(substr($ret_url, strlen($ret_url)-1,1) != '/')
        $ret_url .= '/';
    $lang_code = get_current_language_code();
    $ret_url .= ($lang_code=='en'?'business-map':'carte-des-entreprises').'/?localize_single='.$post_id;
    return $ret_url;
}

function get_business_permalink($post_url)
{
    global $post;
    return return_business_permalink($post->ID);
}
add_filter('the_permalink',"get_business_permalink");


-//SHOW BUSINESSES ON CATEGORY PAGES
add_filter( 'pre_get_posts', 'my_get_posts' );
function my_get_posts( $query ) {

   if ( (is_category()) && !is_category('blog') && false == $query->query_vars['suppress_filters'] ) {
       $query->set( 'post_type', array( 'business' ) );
   }
   else if(is_search() && false == $query->query_vars['suppress_filters'] ) {
       $query->set( 'post_type', array( 'business', 'post' ) );
   }

   return $query;
}



function get_business_main_category($post_id) {
    $main_cat = get_post_meta($post_id, BUSINESS_META_PREFIX.'main_category', true);
    if(!$main_cat) {
        $categories = wp_get_post_categories( $post_id );
        if(count($categories)) {
            $main_cat = get_category($categories[0])->slug;
        }
    }
    return $main_cat;
}


//WIDGETS
add_action( 'widgets_init', 'load_gourmet_widgets' );
function load_gourmet_widgets() {
    include(WP_CONTENT_DIR . '/themes/gourmet/' . 'widgets.php');
    register_widget( 'Recent_Businesses' );
    register_widget( 'Business_Categories' );
    register_widget( 'Business_Activities' );
}

function gourmet_post_count() {
    global $wp_query;
    return $wp_query->found_posts;
}

function echo_pagination_bar($current_number, $post_count ) {

    $last_page = (int)($post_count / 3) + ($post_count % 3?1:0);

    if($last_page > 1) {

        echo '<hr />';
        echo '<div id="pagination"><ul>';

        if($current_number != 1)
          echo get_pagination_list_item(-1,$current_number-1, 'prev');

        for($i = 1; $i <= $last_page; $i++) {
            if($i == $current_number-1 || $i == $current_number || $i == $current_number+1 || $i == 1 || $i == $last_page)
                echo get_pagination_list_item($current_number, $i);
            else {
                if($i == $current_number-2 || $i == $current_number+2) {
                    echo '<li class="empty"></li>';
                }
            }
        }

        if($current_number != $last_page)
          echo get_pagination_list_item(-1,$current_number+1, 'next');

        echo '</ul></div>';
    }

}

function get_pagination_link($page_number, $page_max = 1) {
    $sort = (isset($_GET['sort'])?$_GET['sort']:'');
    $pre_link = ($sort?'?sort='.$sort.'&':'?');
    $link = $pre_link . 'page_num='.$page_number;
    return $link;
}

function get_pagination_list_item($curr_number, $page_num, $extra_classes='') {
    return '<li class="page ' . ($page_num==$curr_number?'active':'') . ' ' . $extra_classes . '"><a href="'
        . get_pagination_link($page_num) . '">'
        . ($extra_classes=='prev'?'Précédent':($extra_classes=='next'?'Suivant':$page_num)) . '</a></li>';
}

function gourmet_pagination_limits($query) {
    $gourmet_limit_offset = 0;
    
    if(isset($_GET['page_num'])) {
        $gourmet_limit_offset = 3 * ($_GET['page_num'] - 1);
    }

    $return_val = "LIMIT ".$gourmet_limit_offset.", 3";

    return $return_val;
}


function gourmet_archive_sorting($query) {
    if(isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        if($sort == 'alpha') {
            return "post_title ASC";
        }
        else if($sort == 'date') {
            return "post_date ASC";
        }
    }
    else {
        return "post_title ASC";
    }
}

function handle_category_exclude($query_string) {
    if(!$query_string)
        return 'cat=-23,-32';
    else if(!strstr($query_string, 'category_name'))
        return $query_string.'&cat=-23,-32';
    return $query_string;
}

function get_current_language_code() {
    global $languages;
    if(!$languages)
        $languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR');

    foreach($languages as $lang) {
        if($lang['active']) {
            $page_language = $lang;
            return $lang['language_code'];
        }
    }
}

function link_to_other_language() {
    global $languages;
    if(!$languages)
        $languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR');
    
    foreach($languages as $lang) {
        if(!$lang['active']) {
            $page_language = $lang;
            return '<a class="language" href="' . $lang['url'] . '"><span class="accron">' . $lang['language_code'] . '</span></a>';
        }
    }

}

add_action('wp_enqueue_scripts', 'include_gourmet_scripts');
function include_gourmet_scripts() {
    global $post;

    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
    wp_register_script('general', get_template_directory_uri() . '/js/general.js');

    wp_enqueue_script('jquery');
    wp_enqueue_script('general');

    wp_register_script('addthis', 'http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e7f6b7f72401f1a');
    wp_enqueue_script('addthis');

    wp_register_script( 'categories', get_template_directory_uri() . '/js/categories.js');
    wp_enqueue_script('categories');

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if($template === 'business-map-page.php') {

        global $wpgeo;
        if($wpgeo) {
            $wp_geo_options = get_option('wp_geo_options');
            $locale = $wpgeo->get_googlemaps_locale('&hl=');

            wp_register_script('googlemaps', 'http://maps.googleapis.com/maps/api/js?file=api&v=3.5' . $locale . '&key=' . $wpgeo->get_google_api_key() . '&sensor=false', false, '2');
            
            wp_register_script( 'map', get_template_directory_uri() . '/js/map.js');
            
            wp_enqueue_script('googlemaps');
            wp_enqueue_script('map');
        }
        else {
            alert('You need to install the WP-Geo plugin in order to display this map.');
        }
    }
    
}

function format_website($url) {
    if(!strstr($url,'http'))
        return 'http://' . $url;
    return $url;
}

function get_businesses_for_map($format = ''){
    $query = new WP_Query('post_type=business&posts_per_page=-1');
    $posts = $query->get_posts();

    for($i = 0; $i < count($posts); $i++) {
        $id = $posts[$i]->ID;
        $lat = get_post_meta($posts[$i]->ID, '_wp_geo_latitude', true);
        $lng = get_post_meta($posts[$i]->ID, '_wp_geo_longitude', true);
        $categories = wp_get_post_categories( $posts[$i]->ID );
        $cat_slug = get_business_main_category( $posts[$i]->ID);
        $terms = get_key_array(get_the_terms( $posts[$i]->ID, 'legend_icons'), 'term_id', true);
        $phone =    get_post_meta($posts[$i]->ID, BUSINESS_META_PREFIX.'phone', true);
        $email =    get_post_meta($posts[$i]->ID, BUSINESS_META_PREFIX.'email', true);
        $website =  get_post_meta($posts[$i]->ID, BUSINESS_META_PREFIX.'website', true);
        $address =  get_post_meta($posts[$i]->ID, BUSINESS_META_PREFIX.'address', true);
        $hours =  get_post_meta($posts[$i]->ID, BUSINESS_META_PREFIX.'hours', true);

        $content = $posts[$i]->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]>', $content);

        $posts[$i]->id = $id;
        $posts[$i]->lat = $lat;
        $posts[$i]->lng = $lng;
        $posts[$i]->categories = $categories;
        $posts[$i]->cat_slug = $cat_slug;
        $posts[$i]->services = $terms;
        $posts[$i]->phone = $phone;
        $posts[$i]->email = $email;
        $posts[$i]->hours = $hours;
        $posts[$i]->website = $website;
        $posts[$i]->address = $address;
        $posts[$i]->content = $content;
    }
    
    if($format == 'json') {
        echo json_encode($posts);
    }
    else {
        return $posts;
    }
}


function get_categories_for_map($format = '') {
    $categories = use_key_as_array_id(get_categories(), 'term_id', true);

    if($format == 'json') {
        echo json_encode($categories);
    }
    else {
        return $categories;
    }
}

function get_services_for_map($format = '') {
    $taxonomies = use_key_as_array_id(get_terms('legend_icons'), 'term_id', true);

    if($format == 'json') {
        echo json_encode($taxonomies);
    }
    else {
        return $taxonomies;
    }
}

function get_key_array($array, $key = 'id', $is_array_of_objects = false) {
    $new_array = array();
    if($array)
    foreach($array as $unit) {
        if($is_array_of_objects)
            $new_array[] = $unit->$key;
        else
            $new_array[] = $unit[$key];
    }
    return $new_array;
}

function use_key_as_array_id($array, $key = 'id', $is_array_of_objects = false) {
    $new_array = array();
    if($array)
    foreach($array as $unit) {
        if($is_array_of_objects)
            $new_array[$unit->$key] = $unit;
        else
            $new_array[$unit[$key]] = $unit;
    }
    return $new_array;
}

function get_cat_slug($cat_id) {
    $cat_id = (int) $cat_id;
    $category = &get_category($cat_id);
    return $category->slug;
}







/*BELOW IS STUFF FROM TWENTY ELEVEN*/
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( dirname( __FILE__ ) . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// The next four constants set how Twenty Eleven supports custom headers.

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to twentyeleven_header_image_width and twentyeleven_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyeleven_header_image_width', 1000 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyeleven_header_image_height', 288 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add Twenty Eleven's custom image sizes
	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true ); // Used for large feature (header) images
	add_image_size( 'small-feature', 500, 300 ); // Used for featured posts if a large-feature doesn't exist
	add_image_size( 'sj-post-thumb', 220, 120, true );

	// Turn on random header image rotation by default.
	add_theme_support( 'custom-header', array( 'random-default' => true ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyeleven_admin_header_style(), below.
	add_custom_image_header( 'twentyeleven_header_style', 'twentyeleven_admin_header_style', 'twentyeleven_admin_header_image' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Higher Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Lower Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Archive Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Pages Higher Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	

	register_sidebar( array(
		'name' => __( 'Footer Area 1', 'twentyeleven' ),
		'id' => 'sidebar-5',
		'description' => __( 'Footer - Left', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '',
		'after_title' => '</br></br>',
	) );


	register_sidebar( array(
		'name' => __( 'Footer Area 2', 'twentyeleven' ),
		'id' => 'sidebar-6',
		'description' => __( 'Footer - Center', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '',
		'after_title' => '</br></br>',
	) );

register_sidebar( array(
		'name' => __( 'Footer Area 3', 'twentyeleven' ),
		'id' => 'sidebar-7',
		'description' => __( 'Footer - Right', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '',
		'after_title' => '</br></br>',
	) );
	
	
	register_sidebar( array(
		'name' => __( 'Adds Sidebar', 'twentyeleven' ),
		'id' => 'adds-sidebar',
		'description' => __( 'An optional widget area for your ads', 'twentyeleven' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title add-widget-title">',
		'after_title' => '</h3>',
	) );




}
add_action( 'widgets_init', 'twentyeleven_widgets_init' );

/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
    	<hr class="post_complete" />
		<nav id="<?php echo $nav_id; ?>">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Articles Précédent', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Plus récents  Articles <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'twentyeleven' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'twentyeleven' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()

if ( ! function_exists( 'twentyeleven_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'twentyeleven' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'twentyeleven' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

/* category extra fields */
add_action ( 'category_edit_form_fields', 'edit_category_extra_fields');
add_action ( 'category_add_form_fields', 'add_category_extra_fields');
//add_action('category_add_form_fields','category_edit_form_fields');
function edit_category_extra_fields($category) 
{
	$term_id = $category->term_id;
	$color_name = "category_".$term_id."_color";
	$category_meta_color = get_option( "$color_name");
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="category_color"><?php _e('Category color') ?></label></th>
		<td>
		<input type="input" name="Category_meta_color" id="Category_meta_color" value="<?php echo $category_meta_color; ?>" > 
		 </td>
	</tr>
	<?php
}
function add_category_extra_fields($category) 
{
	$term_id = $category->term_id;
	$color_name = "category_".$term_id."_color";
	$category_meta_color = get_option( "$color_name");
	?>
    <div class="form-field">
        <label for="category_color"><?php _e('Category color') ?></label>
        <input type="input" name="Category_meta_color" id="Category_meta_color" value="<?php echo $category_meta_color; ?>" size="40">
        <p>Color for each category (example #FFF)</p>
    </div>
    <?php
}
add_action ( 'edited_category', 'save_extra_category_fileds');
add_action ( 'created_category', 'save_extra_category_fileds');
  // save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
	if ( isset( $_POST['Category_meta_color'] ) ) 
	{
		$save_color_name = "category_".$term_id."_color";
		$category_meta = get_option( "$save_color_name");
		//save the option array
		update_option( "$save_color_name", $_POST['Category_meta_color'] );
	}
}
/* category extra fields ends */

/* Legend icons extra fields */

add_action ( 'legend_icons_edit_form_fields', 'edit_legend_icons_extra_fields');
add_action ( 'legend_icons_add_form_fields', 'add_legend_icons_extra_fields');
//add_action('category_add_form_fields','category_edit_form_fields');
function edit_legend_icons_extra_fields($legend_icons) 
{
	$term_id = $legend_icons->term_id;
	$color_name = "legend_icons_".$term_id."_color";
	$legend_icons_meta_color = get_option( "$color_name");
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="legend_icons_color"><?php _e('Legend icon color') ?></label></th>
		<td>
		<input type="input" name="Legend_icons_meta_color" id="Legend_icons_meta_color" value="<?php echo $legend_icons_meta_color; ?>" > 
		 </td>
	</tr>
	<?php
}
function add_legend_icons_extra_fields($legend_icons) 
{
	$term_id = $legend_icons->term_id;
	$color_name = "legend_icons_".$term_id."_color";
	$legend_icons_meta_color = get_option( "$color_name");
	?>
    <div class="form-field">
        <label for="legend_icons_color"><?php _e('Legend icon color') ?></label>
        <input type="input" name="Legend_icons_meta_color" id="Legend_icons_meta_color" value="<?php echo $legend_icons_meta_color; ?>" size="40">
        <p>Color for each legend icon (example #FFF)</p>
    </div>
    <?php
}
add_action ( 'edited_legend_icons', 'save_extra_legend_icons_fileds');
add_action ( 'created_legend_icons', 'save_extra_legend_icons_fileds');
  // save extra category extra fields callback function
function save_extra_legend_icons_fileds( $term_id ) {
	if ( isset( $_POST['Legend_icons_meta_color'] ) ) 
	{
		$save_color_name = "legend_icons_".$term_id."_color";
		$legend_icons_meta = get_option( "$save_color_name");
		//save the option array
		update_option( "$save_color_name", $_POST['Legend_icons_meta_color'] );
	}
}
/* Legend icons extra fields end */

/* parse shortcode for text widget */
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');
/* parse shortcode for text widget ends */

/*shortcode for maplink widget*/
function maplink_func($atts) {
     $result = '<div class="marron-banner big">';
     $result .= '<div class="middle"><a href="'.get_business_map_link().'">';
     $result .= '<img src="'.get_stylesheet_directory_uri().'/images/map-image-'.get_current_language_code().'.png" />';
	 if(get_current_language_code() == "en")
	 {
     	$result .= '<span class="description" style="margin:20px 8px 8px;">Business Map</span>';
	 }
	 else
	 {
		 $result .= '<span class="description">Voir la <br />carte interactive</span>';
	 }
     $result .= '</a></div>';
     $result .= '</div>';
	 return $result;
}
add_shortcode('maplink', 'maplink_func');
/* shortcode for maplink widget ends */

function business_order($post_ID, $post_type = 'business')
{
	$args = array(
    'orderby'         => 'post_date',
    'order'           => 'ASC',
    'post_type'       => $post_type,
    'post_status'     => 'publish' );
	$businessPosts = get_posts($args);
	$p = 1;
	foreach($businessPosts as $businessPost)
	{
		if($businessPost->ID == $post_ID)
		{
			break;
		}
		$p++;
	}
	return $p;
}
?>