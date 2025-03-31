<?php
  // regionタクソノミーの項目を取得
  $regions = get_terms(array(
    'taxonomy' => 'region',
    'hide_empty' => false, // 投稿のないタクソノミーも表示
  ));
  // タクソノミーが存在するか確認
  if (!empty($regions) && !is_wp_error($regions)) {
    foreach ($regions as $region) {
      // フィルター適用済みのproduct一覧ページへのリンクを作成
      $filtered_link = home_url('/product/?region%5B%5D=' . $region->slug . '&keyword=');
      echo '<li><a href="' . esc_url($filtered_link) . '">' . esc_html($region->name) . '</a></li>';
    }
  }
?>