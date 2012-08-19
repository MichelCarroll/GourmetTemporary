jQuery(document).ready(function() {

    select_all_businesses();

    initialize_map();
    initialize_icons();

    subset_markers_to_map([],(single_id != null));

    register_filters();
    register_locate();
    register_street_view();

    register_additionals();

    if(single_index)
        focus_on_single();
});


var categories_class = '.map-categories';
var services_class = '.activites';

var closest_locations_number = 5;
var max_zoom = 19;
var starting_location = new google.maps.LatLng(45.53146108766671, -73.66607666015625);
var geolocation_uri = 'http://maps.googleapis.com/maps/api/geocode/json?';


var map = null;
var current_subset = null;
var current_markers = null;

var my_location_marker = null;
var custom_markers = [];
var single_index = null;

var popup_opened = false;
var is_dragging = false;



function focus_on_single() {
    current_markers[single_index].setAnimation(google.maps.Animation.BOUNCE);
    map.setZoom(14);

    setTimeout(function() {
        current_markers[single_index].setAnimation(null);
    }, 10000);
}


function initialize_map() {

  if(single_index)
    single_position = new google.maps.LatLng(businesses[single_index].lat, businesses[single_index].lng);

  var myOptions = {
    zoom: 11,
    center: (single_index!=null?single_position:starting_location),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    mapTypeControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL,
        position: google.maps.ControlPosition.LEFT_CENTER
    },
    panControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.LEFT_CENTER
    },
    zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.LEFT_CENTER
    },
    maxZoom: max_zoom
  }
  map = new google.maps.Map(document.getElementById("map"),myOptions);


}

function initialize_icons() {

    initialize_icon("detaillant", "dblue");
    initialize_icon("detaillant-3", "dblue");
    initialize_icon("fromage", "blue");
    initialize_icon("fromage-2", "blue");
    initialize_icon("abeille", "yellow");
    initialize_icon("abeille-2", "yellow");
    initialize_icon("tables", "orange");
    initialize_icon("tables-2", "orange");
    initialize_icon("erable", "brown");
    initialize_icon("erable-2", "brown");
    initialize_icon("transforme", "green");
    initialize_icon("transforme-2", "green");
    initialize_icon("fruits-et-legumes", "lgreen");
    initialize_icon("fruits-et-legumes-2", "lgreen");
    initialize_icon("viande", "red");
    initialize_icon("viande-2", "red");
    initialize_icon("marche", "pink");
    initialize_icon("marche-2", "pink");
    initialize_icon("myself", "me", true);

}

function initialize_icon(marker_name, icon_name, is_wide) {

    custom_markers[marker_name] = new google.maps.MarkerImage(theme_dir+"/images/business/map/"+icon_name+".png",
        // This marker is 20 pixels wide by 32 pixels tall.
        new google.maps.Size((is_wide?33:28), 43),
        // The origin for this image is 0,0.
        new google.maps.Point(0,0),
        // The anchor for this image is the base of the flagpole at 0,32.
        new google.maps.Point(13, 43));

}


function clear_current_markers() {
  if (current_markers) {
    for (i in current_markers) {
      current_markers[i].setMap(null);
    }
  }
  current_subset = new Array();
  current_markers = new Array();
}

function subset_markers_to_map(extra_markers, no_fit) {

    current_markers = new Array();
    var bounds = new google.maps.LatLngBounds();

    for(i in current_subset) {
        var latLng = new google.maps.LatLng(businesses[current_subset[i]].lat, businesses[current_subset[i]].lng);

        current_markers[i] =
            new google.maps.Marker({
                position: latLng,
                map: map,
                icon: custom_markers[businesses[current_subset[i]].cat_slug],
                title: businesses[current_subset[i]].post_title
            });


        register_popups(current_markers[i], businesses[current_subset[i]].id);

        bounds.extend(latLng);
    }

    if(extra_markers)
    for(i in extra_markers) {
        bounds.extend(extra_markers[i]);
    }

    if(no_fit == undefined || no_fit == false) {
        map.fitBounds(bounds);
    }
}

function convertPoint(latLng) {
     var topRight=map.getProjection().fromLatLngToPoint(map.getBounds().getNorthEast());
     var bottomLeft=map.getProjection().fromLatLngToPoint(map.getBounds().getSouthWest());
     var scale=Math.pow(2,map.getZoom());
     var worldPoint=map.getProjection().fromLatLngToPoint(latLng);
     return new google.maps.Point((worldPoint.x-bottomLeft.x)*scale,(worldPoint.y-topRight.y)*scale);
}

function register_street_view() {
    
    var thePanorama = map.getStreetView();

    google.maps.event.addListener(thePanorama, 'visible_changed', function() {

        if (thePanorama.getVisible()) {

            jQuery('#entreprise-map-area').attr('class', 'street_view');

        } else {

            jQuery('#entreprise-map-area').removeAttr('class', '');

        }

    });
    
}


function register_popups(marker, business_id) {

    google.maps.event.addListener(marker, 'mouseover', function(event, mevent, yevent) {

        if(!popup_opened && !is_dragging) {
            var summary = jQuery('#business-summary-'+business_id);
            var map_area = jQuery('#entreprise-map-area');
            var offset = jQuery(map_area).offset();

            var position =  convertPoint(this.getPosition());

            summary.css('left', position.x - 28);
            summary.css('top', position.y - 230);
            summary.fadeIn(200);
        }

    });


    google.maps.event.addListener(marker, 'mouseout', function() {
        jQuery('#business-summary-'+business_id).fadeOut(200);
    });

    google.maps.event.addListener(marker, 'mousedown', function(event, mevent, yevent) {

        if(!popup_opened) {
            var summary = jQuery('#business-popup-'+business_id);
            var map_area = jQuery('#entreprise-map-area');
            var offset = jQuery(map_area).offset();

            summary.css('left', offset.left - 141.5);
            summary.css('top', offset.top - 255);
            summary.fadeIn(200);
            popup_opened = true;
        }

    });

    jQuery('#business-popup-'+business_id+' .close, #business-popup-'+business_id+' .activites li a').click(function() {
        var summary = jQuery('#business-popup-'+business_id);
        summary.fadeOut(200);
        popup_opened = false;
    });
}

function select_all_businesses() {
    current_subset = new Array();
    for(i in businesses) {
        if(businesses[i].id ==  single_id) {
            single_index = i;
        }
        current_subset[i] = i;
    }
}


function register_filters() {
    jQuery(categories_class).click(function() {
        var id = jQuery(this).attr('id');

        clear_current_markers();
        filter_categories(id);

        if(current_subset.length)
            subset_markers_to_map();

    });

    jQuery(services_class + ' a').click(function() {
        var id = jQuery(this).attr('id');

        clear_current_markers();
        filter_services(id);

        if(current_subset.length)
            subset_markers_to_map();

    });
}

function filter_categories(id) {
    current_subset = new Array();
    for(i in businesses) {
        if(jQuery.inArray( id, businesses[i].categories ) != -1) {
            current_subset[i] = i;
        }
    }
}

function filter_services(id) {
    current_subset = new Array();
    for(i in businesses) {
        if(jQuery.inArray( id, businesses[i].services ) != -1) {
            current_subset[i] = i;
        }
    }
}

function register_locate() {

    jQuery('#my_location').click(function() {
        if(jQuery(this).hasClass('initial')) {
            jQuery(this).attr('value','');
            jQuery(this).removeClass('initial');
        }
    });

    jQuery('#my_location').keypress(function(event) {
        if ( event.which == 13 ) {
            search_for_my_location(jQuery('#my_location').val());
            jQuery('#my_location').addClass('initial');
            event.preventDefault();
        }
    });


    jQuery('#locate_btn').click(function() {
        if(!(jQuery('#my_location').hasClass('initial'))) {
            search_for_my_location(jQuery('#my_location').val());
            jQuery('#my_location').addClass('initial');
        }
    });
}


function search_for_my_location(address) {
    execute_geolocation(address, focus_on_my_location);
}


function execute_geolocation(address, callback) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'address': address,
        'region':'ca'
        },
        function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                callback(results);
            }
            else {
                alert('Address was not found!');
            }
        });
}

function focus_on_my_location(geolocate_results) {
    var my_location = geolocate_results[0].geometry.location;

    clear_current_markers();
    if(my_location_marker) {
        my_location_marker.setMap(null);
    }

    my_location_marker = new google.maps.Marker({
        map: map,
        icon: custom_markers["myself"],
        position: my_location
    });

    subset_nearest_businesses(my_location, closest_locations_number);
    subset_markers_to_map([my_location]);
}

function subset_nearest_businesses(root_location, n) {

    var lat = root_location.lat();
    var lng = root_location.lng();
    var R = 6371;
    var distances = [];
    var closest = [];

    for( i=0;i<businesses.length; i++ ) {
        var mlat = businesses[i].lat;
        var mlng = businesses[i].lng;
        var dLat  = rad(mlat - lat);
        var dLong = rad(mlng - lng);
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong/2) * Math.sin(dLong/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        distances[i] = d;
    }

    for(var x = 0; x < n; x++)
    {
        var min_index = minIndex(distances);
        current_subset.push(min_index);
        distances[min_index] = null;
    }


}

function rad(x) {return x*Math.PI/180;}
function sortNumber(a,b)
{
    return a - b;
}
function minIndex(array) {
    var min = -1;
    var minIndex = 0;
    for (i in array) {
        if (array[i] != null && (array[i] < min || min == -1)) {
            min = array[i];
            minIndex = i;
        }
    }
    return minIndex;
}

function register_additionals() {

    google.maps.event.addListener(map, 'dragstart', function() {
        is_dragging = true;
    });
    google.maps.event.addListener(map, 'dragend', function() {
        is_dragging = false;
    });
}