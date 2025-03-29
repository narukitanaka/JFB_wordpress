<?php
$thumbnail = get_the_post_thumbnail_url(get_the_ID(), '');
$regions = get_the_terms(get_the_ID(), 'region');
?>

<div class="companyCard01">
  <div>
    <div class="left">
      <?php if ($thumbnail) : ?>
        <div class="img-box obj-fit"><img src="<?php echo esc_url($thumbnail); ?>" alt=""></div>
      <?php else : ?>
        <div class="img-box obj-fit"><img src="<?php echo get_template_directory_uri(); ?>/images/noimage.jpg" alt=""></div>
      <?php endif; ?>
    </div>
    <div class="right">
      <div class="info">
        <a href="<?php the_permalink(); ?>">
          <div class="cate">
            <?php get_template_part('inc/snipets-cate'); ?>
          </div>
          <div class="name">
            <?php if (get_field('maker_logo')) : ?>
              <?php $maker_logo = get_field('maker_logo'); ?>
              <span class="logo img-box"><img src="<?php echo esc_url($maker_logo); ?>" alt=""></span>
            <?php endif; ?>
            <?php the_title(); ?>
          </div>
          <div class="txt">
            <?php the_content(); ?>
          </div>
          <?php if ($regions && !is_wp_error($regions) && !empty($regions)) : ?>
            <div class="region">
              <img src="<?php echo get_template_directory_uri(); ?>/images/icon-pin.svg" alt="region">
              <span><?php echo esc_html($regions[0]->name); ?></span>
            </div>
          <?php endif; ?>
        </a>
      </div>
      <div class="links">
        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#profile">Company Profile</a></div>
        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#export">Export Conditions</a></div>
        <div class="btn-wrap"><a class="btn bgc-wh" href="<?php the_permalink(); ?>#product">Product List</a></div>
      </div>
    </div>
  </div>
</div>
