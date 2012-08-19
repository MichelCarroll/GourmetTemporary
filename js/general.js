jQuery(document).ready(function() {

    jQuery('input').each(function() {
        if(jQuery(this).attr('replaced'))
            jQuery(this).val(jQuery(this).attr('replaced'));
    });

    jQuery('input').focus(function() {
        if(jQuery(this).attr('replaced')) {
            jQuery(this).val('');
        }
    });

});
