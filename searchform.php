<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

<div class="search">
    <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input name="s" id="s"  type="text" class="dark-background"  replaced="<?php _e('Click here for a custom search'); ?>" value="" />
        <input type="submit" name="submit"  id="searchsubmit"  value="" />
    </form>
</div>