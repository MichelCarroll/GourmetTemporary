jQuery(document).ready(function() {

    register_cat_popup();

});


var cat_popup_opened = false;
var cat_popup_opening = false;

var cat_popup_timeout = null;

function register_cat_popup() {

    jQuery('.par-produits').hover(function() {
        make_cat_popup_appear();
        resetCatPopupCloseTimer();
    }, function() {
        startCatPopupCloseTimer();
    });

    jQuery('#category-popup').hover(function() {
        resetCatPopupCloseTimer();
    }, function() {
        startCatPopupCloseTimer();
    });
}

function make_cat_popup_appear() {
    if(!cat_popup_opened && !cat_popup_opening) {
        cat_popup_opening = true;
        jQuery('#category-popup').fadeIn(200, function() {
            cat_popup_opening = false;
            cat_popup_opened = true;
        });
    }
}

function startCatPopupCloseTimer() {
    cat_popup_timeout = setTimeout("close_cat_popup()",100);
}

function resetCatPopupCloseTimer() {
    if(cat_popup_timeout) {
        clearTimeout(cat_popup_timeout);
    }
}

function close_cat_popup() {
    if(cat_popup_opened) {
        jQuery('#category-popup').fadeOut(200, function() {
            cat_popup_opened = false;
        });
    }
}
