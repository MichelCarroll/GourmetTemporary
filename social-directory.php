<?php
    $to_share = ' addthis:url="'.return_business_permalink($post->ID).'" addthis:title="'. get_the_title().'"';
    ?>
<ul class="social-icons">
  <li><a class="addthis_button_email" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_facebook" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_twitter" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_digg" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_reddit" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_delicious" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_stumbleupon" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_linkedin" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_googlebuzz" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_google_plusone" <?php echo $to_share; ?>></a></li>
</ul>