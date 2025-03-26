<?php
  $post_id = get_the_ID();
  $product_cats = get_the_terms($post_id, 'product-cat');
  
  if (!empty($product_cats) && !is_wp_error($product_cats)) {
    $categories_by_parent = array();// 親カテゴリーと子カテゴリーを整理
    // すべてのカテゴリーを親IDでグループ化
    foreach ($product_cats as $cat) {
      $parent_id = $cat->parent;
      if (!isset($categories_by_parent[$parent_id])) {
        $categories_by_parent[$parent_id] = array();
      }
      $categories_by_parent[$parent_id][] = $cat;
    }
    // 親カテゴリー（parent_id = 0）が存在する場合
    if (isset($categories_by_parent[0])) {
      // 各親カテゴリーとその子カテゴリーを順番に表示
      foreach ($categories_by_parent[0] as $parent) {
        // 親カテゴリーを表示
        $color = get_field('cate_color', 'product-cat_' . $parent->term_id);
        echo '<span class="parent" style="background-color: ' . esc_attr($color) . '; border-color: ' . esc_attr($color) . ';">' . esc_html($parent->name) . '</span>';
        // この親に属する子カテゴリーを表示
        if (isset($categories_by_parent[$parent->term_id])) {
          foreach ($categories_by_parent[$parent->term_id] as $child) {
            echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($child->name) . '</span>';
          }
        }
      }
    }
    // 親がないか、親が設定されていない子カテゴリーを処理
    foreach ($product_cats as $cat) {
      if ($cat->parent != 0 && !isset($categories_by_parent[0])) {
        // 親カテゴリーが存在するか確認
        $parent_term = get_term($cat->parent, 'product-cat');
        if (!is_wp_error($parent_term) && $parent_term) {
          $color = get_field('cate_color', 'product-cat_' . $parent_term->term_id);
          if (!$color) $color = '#cccccc'; // デフォルトの色
          echo '<span class="child" style="border-color: ' . esc_attr($color) . '; color: ' . esc_attr($color) . ';">' . esc_html($cat->name) . '</span>';
        } else {
          // 親が見つからない場合は標準表示
          echo '<span class="child">' . esc_html($cat->name) . '</span>';
        }
      }
    }
  }
?>