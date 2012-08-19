

var business_slides = null;
var number_features = 0;
var current_index = 0;
var in_transition = true;
var fade_speed = 1000;
var slide_speed = 6000;
var slide_interval = null;

jQuery(document).ready(function() {

    business_slides = jQuery('#featured-businesses .business');
    number_features = business_slides.length;

    if(number_features == 1) {
        jQuery(business_slides[0]).show();
    }
    else if(number_features > 1) {
        jQuery(business_slides).each(function() {
            jQuery(this).hide();
        });

        jQuery(business_slides[0]).show();
        in_transition = false;

        slide_interval = setInterval("nextSlide()", slide_speed);
    }

    jQuery('#featured-slider .controls a.left').click(function() {
        clearInterval(slide_interval);
        prevSlide();
    });

    jQuery('#featured-slider .controls a.right').click(function() {
        clearInterval(slide_interval);
        nextSlide();
    });


});

function prevSlide() {
    if(!in_transition) {
        in_transition = true;
        var slide_to_show = current_index;

        if(current_index > 0)
            slide_to_show--;
        else
            slide_to_show = number_features - 1;

        show_slide(slide_to_show);
    }
}

function nextSlide() {
    if(!in_transition) {
        in_transition = true;
        var slide_to_show = current_index;

        if(current_index < number_features - 1)
            slide_to_show++;
        else
            slide_to_show = 0;

        show_slide(slide_to_show);
    }
}

function show_slide(index_to_show) {
    jQuery(business_slides[current_index]).fadeOut(fade_speed, function() {
        var next_index = index_to_show;
        jQuery(business_slides[next_index]).fadeIn(fade_speed, function() {
            in_transition = false;
            current_index = index_to_show;
        });
    });
}