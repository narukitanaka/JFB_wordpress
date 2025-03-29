<?php
// 地域を取得
$regions = get_the_terms(get_the_ID(), 'region');
// メーカー関連投稿を取得
$maker_post = get_field('item_maker');
// メーカー名を取得
$maker_name = '';
if ($maker_post) {
  if (is_array($maker_post) && isset($maker_post[0])) {
    $first_maker = $maker_post[0];
    $maker_name = is_object($first_maker) ? $first_maker->post_title : 
    (isset($first_maker['post_title']) ? $first_maker['post_title'] : 
    get_the_title($first_maker));
  }
}
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