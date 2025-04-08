<?php
// 地域を取得
$regions = get_the_terms(get_the_ID(), 'region');
// メーカー関連投稿を取得
$maker_post = get_field('item_maker');
// メーカー名とURLを取得
$maker_name = '';
$maker_url = '';
$maker_id = 0;
if ($maker_post) {
  if (is_array($maker_post) && isset($maker_post[0])) {
    $first_maker = $maker_post[0];    
    // メーカーIDを取得
    if (is_object($first_maker)) {
      $maker_id = $first_maker->ID;
      $maker_name = $first_maker->post_title;
    } elseif (isset($first_maker['post_title'])) {
      $maker_id = $first_maker['ID'];
      $maker_name = $first_maker['post_title'];
    } else {
      $maker_id = $first_maker;
      $maker_name = get_the_title($first_maker);
    }
    // メーカーのURLを取得
    if ($maker_id) {
      $maker_url = get_permalink($maker_id);
    }
  }
}
?>

<div class="itemCard">
  <div class="favorite-button-container">
    <?php echo do_shortcode('[favorite_button]'); ?>
  </div>
  <a href="<?php the_permalink(); ?>">
    <div class="img-box obj-fit">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail(); ?>
      <?php else : ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/noimage01.png" alt="<?php the_title(); ?>">
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
  <?php if( is_page('mypage') ): ?>
    <div class="btn-wrap">
      <a class="btn bgc-re" href="<?php echo esc_url($maker_url); ?>/#sendmail">Contact Maker</a>
    </div>
  <?php endif; ?>
</div>