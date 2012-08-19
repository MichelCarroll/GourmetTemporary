<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="ns_widget_mailchimp-email-4" method="post">

    <input type="hidden" name="ns_mc_number" value="4" />
    <input type="hidden" name="mailing_list_id" value="<?php echo __('9e386f0cae'); ?>" />
    <nobr>
    <input type="text" class="dark-background" name="ns_widget_mailchimp_email"  id="ns_widget_mailchimp-email-4" replaced="<?php _e('Enter your email here'); ?>" />
    <input type="submit"  value="" />
    </nobr>
    
</form>
<script>jQuery('#ns_widget_mailchimp-email-4').ns_mc_widget({"url" : "<?php echo $_SERVER['PHP_SELF']; ?>", "loader_graphic" : "<?php echo plugins_url(); ?>/mailchimp-widget/images/ajax-loader.gif"}); </script>