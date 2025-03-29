<?php
$regions = isset($args['regions']) ? $args['regions'] : null;
$maker_name = isset($args['maker_name']) ? $args['maker_name'] : '';
?>

<div class="itemCard">
  <a href="<?php the_permalink(); ?>">
    <div class="img-box obj-fit">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail(); ?>
      <?php else : ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt="<?php the_title(); ?>">
      <?php endif; ?>
    </div>
    <div class="cate">
      <?php get_template_part('inc/snipets-cate'); ?>
    </div>
    <div class="name"><?php the_title(); ?></div>
    <div class="maker"><?php echo esc_html($maker_name); ?></div>
    <div class="region">
      <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="">
      <?php 
      if ($regions && !is_wp_error($regions) && !empty($regions)) {
        $region = $regions[0];
        echo '<span>' . esc_html($region->name) . '</span>';
      }
      ?>
    </div>
  </a>
</div>