



<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/feature.js"></script>



  <div id="featured-slider">

      <div id="featured-banner" class="<?php echo get_current_language_code(); ?>"></div>

      <div id="featured-businesses">



              <?php



            $temp_query = $wp_query;



            $args = array( 'post_type' => 'business', 'posts_per_page' => 3, 'meta_key' => '_business_featured', 'meta_value' => 1);

            $loop = new WP_Query( $args );

            

            //FALLBACK IF NO STARRED BUSINESSES

            if(!$loop->have_posts()){

                $args = array( 'post_type' => 'business', 'posts_per_page' => 3, 'orderby' => 'date', 'order' => 'ASC');

                $loop = new WP_Query( $args );

            }

            while ( $loop->have_posts() ) { $loop->the_post();



            ?>



          <div class="business">

              <?php
                    //$img_url = array(get_stylesheet_directory_uri() . '/images/slider-default.jpg',220, 120, false);
					$img_url = get_stylesheet_directory_uri() . '/images/slider-default.jpg';

                    if(has_post_thumbnail ($post->ID)) {
						$catDet = get_the_category($post->ID);
						$cat_slug = get_business_main_category( $post->ID);
						if(!empty($catDet))
						{
							$img_url = get_stylesheet_directory_uri() . '/images/business/category/' . $cat_slug . '.png';
						}
                        //$img_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large');
                    }
				//echo '<img width="35" src="' . get_stylesheet_directory_uri() . '/images/business/category/' . $catDet[0]->slug . '.png" />';
                ?>
              <img width="285" height="280" src="<?php echo $img_url; ?>" />

              <div class="description">

                  <span class="meta-information"><?php echo get_the_date(); ?> &bull; <?php the_category(', '); ?></span>

                  <h2><a href="<?php echo return_business_permalink($post->ID); ?>"><?php the_title(); ?></a></h2>

                  <?php the_excerpt(); ?>

                  <div class="bottom">

                      <hr class="dotted" />
                      <a  href="<?php echo return_business_permalink($post->ID); ?>" class="more" >&raquo; <?php echo __('Keep Reading'); ?></a>
                  </div>

              </div>

              <div class="clear"></div>

          </div>

          

            <?php

            }

                

                $wp_query = $temp_query;

            ?>

      </div>

      <div class="controls">

          <a class="pointer left"></a>

          <a class="pointer right"></a>

      </div>

  </div>

