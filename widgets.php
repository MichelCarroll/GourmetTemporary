<?php



class Recent_Businesses extends WP_Widget {

    function Recent_Businesses() {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'Recent_Businesses',
            'description' => __('A widget that displays Carte du Gourmet\'s most recent businesses') );

        /* Widget control settings. */
        $control_ops = array(
            'width' => 300,
            'height' => 350,
            'id_base' => 'recent-businesses' );

        /* Create the widget. */
        $this->WP_Widget( 'recent-businesses', __('Recent Businesses'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $num = $instance['number'];

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ( $title )
                echo $before_title . $title . $after_title;

        $args = array( 'post_type' => 'business', 'posts_per_page' => $num );
        $loop = new WP_Query( $args );
        echo '<div class="block"><ul class="elements">';
        while ( $loop->have_posts() ) : $loop->the_post();
            echo '<li><a href="';
            echo return_business_permalink(get_the_ID());
            echo '" rel="bookmark" title="Permanent Link to ';
            the_title_attribute();
            echo '">';

            $cat_slug = get_business_main_category(get_the_ID());
            if($cat_slug)
                $src = get_stylesheet_directory_uri() . '/images/business/category/' . $cat_slug . '.png';
            else
                $src = get_stylesheet_directory_uri() . '/images/default-icon.jpg';

            echo '<img width="35" heigth="35" src="' . $src . '" />';
            echo '<span class="description"><span class="title">';
            the_title();
            echo '</span><span class="meta-information">';
            echo get_the_date();
            echo '</span></span>';
            echo '</a></li>';
        endwhile;
        echo '</ul></div>';

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['number'] = strip_tags( $new_instance['number'] );

        return $instance;
    }

    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => 'Example', 'name' => 'John Doe', 'sex' => 'male', 'show_sex' => true );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of Businesses:</label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:100%;" />
        </p><?php
    }

}





class Business_Categories extends WP_Widget {

    function Business_Categories() {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'Business_Categories',
            'description' => __('A widget that displays Carte du Gourmet\'s categories') );

        /* Widget control settings. */
        $control_ops = array(
            'width' => 300,
            'height' => 350,
            'id_base' => 'business-categories' );

        /* Create the widget. */
        $this->WP_Widget( 'business-categories', __('Business_Categories'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );
		$exclude_categories = $instance['exclude-cat'];
        $num = $instance['number'];

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ( $title )
                echo $before_title . $title . $after_title;

        $categories = get_categories(array('hide_empty' => 0, 'exclude' => $exclude_categories ));
        echo '<div class="block"><ul class="elements">';
        foreach($categories as $cat) {
			//echo $cat->cat_ID;
			
			$color_name = "category_".$cat->cat_ID."_color";
      $idName = 'category-'.$cat->cat_ID;
			$category_meta_color = get_option( "$color_name");
			
            if(!strstr($cat->slug, 'blog') && !strstr($cat->slug, 'uncategorized') && !strstr($cat->slug, 'marche')) {
                echo '<li rel="'.$category_meta_color.'" id="'.$idName.'"><a href="';
                echo get_category_link($cat->cat_ID);
                echo '" rel="bookmark" title="Permanent Link to ';
                echo $cat->cat_name;
                echo '">';
                echo '<img width="35" src="' . get_stylesheet_directory_uri() . '/images/business/category/' . $cat->slug . '.png" />';
                echo '<span class="description"><span class="title" rel="'.$category_meta_color.'">';
                echo $cat->cat_name;
                echo '</span><span class="meta-information">';
                echo $cat->count . ' ' . __('Businesses');
                echo '</span></span>';
                echo '</a></li><style> ul.elements li#'.$idName.':hover span.title { color:'.$category_meta_color.'; } </style>';
            }
        }
        echo '</ul></div>';

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags( $new_instance['title'] );
		$instance['exclude-cat'] = strip_tags( $new_instance['exclude-cat'] );
        
        return $instance;
    }

    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => 'Example', 'name' => 'John Doe', 'sex' => 'male', 'show_sex' => true );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
            <label for="<?php echo $this->get_field_id( 'exclude-cat' ); ?>">Exclude categories:</label>
            <input id="<?php echo $this->get_field_id( 'exclude-cat' ); ?>" name="<?php echo $this->get_field_name( 'exclude-cat' ); ?>" value="<?php echo $instance['exclude-cat']; ?>" style="width:100%;" />
        </p><?php
    }

}




class Business_Activities extends WP_Widget {

    function Business_Activities() {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'Business_Activities',
            'description' => __('A widget that displays Carte du Gourmet\'s business activities') );

        /* Widget control settings. */
        $control_ops = array(
            'width' => 300,
            'height' => 350,
            'id_base' => 'business-activities' );

        /* Create the widget. */
        $this->WP_Widget( 'business-activities', __('Business_Activities'), $widget_ops, $control_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $num = $instance['number'];
		

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ( $title )
                echo $before_title . $title . $after_title;

        $activities = get_terms('legend_icons', array('hide_empty' => 0));
        echo '<div class="block"><ul class="elements">';
        foreach($activities as $act) {
			//print_r($act);
			//echo "rajesh";
      $idName = 'activity-'.$act->term_id;
			$color_name = "legend_icons_".$act->term_id."_color";
			$legend_icons_color = get_option( "$color_name");
			//echo $legend_icons_color;
            echo '<li rel="'.$legend_icons_color.'" id="'.$idName.'"><a href="';
            echo get_term_link($act);
            echo '" rel="bookmark" title="Permanent Link to ';
            echo $act->name;
            echo '">';
            echo '<img width="35" src="' . get_stylesheet_directory_uri() . '/images/business/activity/' . $act->slug . '.gif" />';
            echo '<span class="description"><span class="title">';
            echo $act->name;
            echo '</span><span class="meta-information">';
            echo $act->count . ' ' . __('Businesses');
            echo '</span></span>';
            echo '</a></li><style> ul.elements li#'.$idName.':hover span.title { color:'.$legend_icons_color.'; } </style>';
        }
        echo '</ul></div>';

        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => 'Example', 'name' => 'John Doe', 'sex' => 'male', 'show_sex' => true );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p><?php
    }

}