<?php
  // product-catの親カテゴリー（parent=0）を取得
  $parent_categories = get_terms(array(
    'taxonomy' => 'product-cat',
    'parent' => 0,
    'hide_empty' => false, // 投稿のないカテゴリーも表示
    'meta_key' => 'category_order', // ACFで設定したフィールド名
    'orderby' => 'meta_value_num', // 数値としてソート
    'order' => 'ASC', // 昇順（小さい数字が先）
  ));
  // カテゴリーが存在するか確認
  if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
    foreach ($parent_categories as $category) {
      // フィルター適用済みのproduct一覧ページへのリンクを作成
      $filtered_link = home_url('/product/?category%5B%5D=' . $category->slug . '&keyword=');
      echo '<li><a href="' . esc_url($filtered_link) . '">' . esc_html($category->name) . '</a></li>';
    }
  }
?>