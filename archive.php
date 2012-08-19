<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */
get_header();

        //add_filter('post_limits', 'gourmet_pagination_limits');
        //add_filter('posts_orderby', 'gourmet_archive_sorting' );
//business_order();
        ?>
        <div id="content" class="directory">
        <?php
		//echo $query_string;
		/*$query_string_exp = explode("=", $query_string);
		$query_string = 'cat='.$query_string_exp[1];
        $query_string = handle_category_exclude($query_string);*/
		//print_r($query_string);
		$paged = 0;
		if(isset($_GET['page_num']))
		{
			$paged = $_GET['page_num'];
		}
		$quered = $wp_query->query_vars;
		query_posts(array ( $wp_query->query, 'paged' =>$paged, 'post_type' => 'business'));
        $post_count = gourmet_post_count();
        if ( have_posts() ) : ?>


            <div id="sorting">
              <div id="category-popup"><div class="block"><ul class="elements">

                <?php $categories = get_categories(array('hide_empty' => 0));
                foreach($categories as $cat) {
                    if(!strstr($cat->slug, 'blog') && !strstr($cat->slug, 'uncategorized') && !strstr($cat->slug, 'marche')) {
                        echo '<li><a href="' . get_category_link($cat->term_id) . '" class="map-categories" id="' . $cat->slug . '">';
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
              <span class="sort-message"><?php _e('Sort the '); ?><br><?php _e('results'); ?></span>
              <div class="sort-controls">
                  <a href="#" class="par-produits"><?php _e('Catégories'); ?><span class="tooltip center silver"><?php _e('Filter the results by category'); ?></span></a>
                  <a href="?sort=alpha" class="par-alpha"><span class="tooltip center silver"><?php _e('Sort the results in alphabetical order'); ?></span></a>
                  <a href="?sort=date" class="par-date"><span class="tooltip center silver"><?php _e('Sort the results in publication order'); ?></span></a>
              </div>
            </div>

                <ul id="listing">
                <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', get_post_type() ); ?>

                <?php endwhile; ?>
                </ul>
                <?php //wp_pagenavi(); ?>

                <?php
                    $current_page = (isset($_GET['page_num'])?$_GET['page_num']:1);
                    echo_pagination_bar($current_page, $post_count);
                ?>
            
        <?php else : ?>

                <h1 class="no-found entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>

                <div class="entry-content">
                        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
                </div><!-- .entry-content -->
                
        <?php endif; ?>


        </div><!-- #content -->


<?php get_sidebar(); ?>
<?php get_footer(); ?>