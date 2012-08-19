<?php
    $to_share = ' addthis:url="'.return_business_permalink($post->ID).'" addthis:title="'. get_the_title().'"';
    ?>
<ul class="social-icons">
  <li><a class="addthis_button_twitter" <?php echo $to_share; ?>></a></li>
  <li><a class="addthis_button_facebook" <?php echo $to_share; ?>></a></li>
</ul>